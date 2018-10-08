<?php

namespace App\Admin\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Http\Requests\HandleRefundRequest;
use App\Models\CrowdfundingProduct;
use App\Services\OrderService;
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
               $actions->append('<a class="btn btn-xs btn-primary" href="' . route('admin.orders.show', [$actions->getKey()]) .'">查看</a>');
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

    public function show(Order $order)
    {
        return Admin::content(function (Content $content) use ($order) {
            $content->header('查看订单');
            $content->body(view('admin.orders.show', ['order' => $order]));
        });
    }

    public function ship(Order $order, Request $request)
    {
        if (!$order->paid_at) {
            throw new InvalidRequestException('该订单未付款');
        }

        if ($order->ship_status !== Order::SHIP_STATUS_PENDING) {
            throw new InvalidRequestException('该订单已发货');
        }

        // 众筹订单只有在众筹成功后发货
        if($order->type === Order::TYPE_CROWDFUNDING &&
            $order->items[0]->product->crowdfunding->status !== CrowdfundingProduct::STATUS_SUCCESS) {
            throw new  InvalidRequestException('众筹订单只能在众筹成功之后发货');
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
            'ship_data'   => $data,
        ]);

        return redirect()->back();
    }

    public function handleRefund(Order $order, HandleRefundRequest $request, OrderService $orderService)
    {
        if ($order->refund_status !== Order::REFUND_STATUS_APPLIED) {
            throw new InvalidRequestException('订单状态不正确');
        }

        if ($request->input('agree')) {
            $orderService->refundOrder($order);
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
}
