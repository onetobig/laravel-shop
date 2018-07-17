<?php

namespace App\Admin\Controllers;

use App\Exceptions\InternalException;
use App\Exceptions\InvalidRequestException;
use App\Http\Requests\Admin\HandleRefundRequest;
use Illuminate\Http\Request;
use App\Models\Order;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class OrdersController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('订单列表');
            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
    }

    public function show(Order $order)
    {
        return Admin::content(function (Content $content) use ($order) {
            $content->header('查看订单');
            $content->body(view('admin.orders.show', compact('order')));
        });
    }
    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Order::class, function (Grid $grid) {
            $grid->model()->whereNotNull('paid_at')->orderBy('paid_at', 'desc');
            $grid->no('订单流水号');
            $grid->column('user.name', '买家');
            $grid->total_amount('总金额')->sortable();
            $grid->paid_at('支付时间')->sortable();
            $grid->ship_status('物流')->display(function ($value) {
                return Order::$shipStatusMap[$value];
            });
            $grid->refund_status('退款状态')->display(function ($value) {
                return Order::$refundStatusMap[$value];
            });
            $grid->disableCreateButton();
            $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableEdit();
                $url = route('admin.orders.show', [$actions->getKey()]);
                $showBtn = <<<EOT
<a class="btn btn-xs btn-primary" href="{$url}">查看</a>
EOT;
                $actions->append($showBtn);
            });
            $grid->tools(function ($tools) {
                $tools->batch(function ($batch) {
                   $batch->disableDelete();
                });
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Order::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }

    public function ship(Order $order, Request $request)
    {
        if (!$order->paid_at) {
            throw new InvalidRequestException('该订单未支付');
        }

        if ($order->ship_status !== Order::SHIP_STATUS_PENDING) {
            throw new InvalidRequestException('该订单已发货');
        }

        $data = $this->validate($request, [
            'express_company' => ['required'],
            'express_no'      => ['required'],
        ], [], [
            'express_company' => '物流公司',
            'express_no'      => '物流单号',
        ]);

        $order->update([
            'ship_status' => Order::SHIP_STATUS_DELIVERED,
            'ship_data' => $data,
        ]);

        return redirect()->back();
    }

    public function handleRefund(Order $order, HandleRefundRequest $request)
    {
        if ($order->refund_status !== Order::REFUND_STATUS_APPLIED) {
            throw new InvalidRequestException('订单状态不正确');
        }

        if ($request->input('agree')) {
            $order = $this->_refundOrder($order);
        } else {
            $extra = $order->extra ?: [];
            $extra['refund_disagree_reason'] = $request->input('reason');
            $order->update([
                'refund_status' => Order::REFUND_STATUS_PENDING,
                'extra'         => $extra,
            ]);
        }
        return $order;
    }

    protected function _refundOrder($order)
    {
        switch ($order->payment_method) {
            case 'wechat':
                break;
            case 'alipay':
                $refundNo = Order::getAvailableRefundNo();
                $ret = app('alipay')->refund([
                    'out_trade_no' => $order->no,
                    'refund_amount' => $order->total_amount,
                    'out_request_no' => $refundNo,
                ]);
                if ($ret->sub_code) {
                    // 退款失败，保存失败信息
                    $extra = $order->extra ?: [];
                    $extra['refund_failed_code'] = $ret->sub_code;
                    $order->update([
                        'refund_no' => $refundNo,
                        'refund_status' => Order::REFUND_STATUS_FAILED,
                        'extra' => $extra,
                    ]);
                } else {
                    // 退款成功,保存退款订单号
                    $order->update([
                        'refund_no' => $refundNo,
                        'refund_status' => Order::REFUND_STATUS_SUCCESS,
                    ]);
                }
                break;
            default:
                throw new InternalException('未知订单支付方式：' . $order->payment_method);
                break;
        }
        return $order;
    }
}
