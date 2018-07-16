<?php

namespace App\Http\Controllers;

use App\Events\OrderReviewed;
use App\Exceptions\InternalException;
use App\Exceptions\InvalidRequestException;
use App\Http\Requests\ApplyRefundRequest;
use App\Http\Requests\HandleRefundRequest;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\SendReviewRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function store(OrderRequest $request, OrderService $orderService)
    {
        $user = $request->user();
        $order = $orderService->store($user, $request->address_id, $request->items, $request->remark);
        return $order;
    }

    public function index(Request $request)
    {
        $orders = Order::query()
            ->with(['items.product', 'items.productSku'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate();

        return view('orders.index', ['orders' => $orders]);
    }

    public function show(Order $order, Request $request)
    {
        $this->authorize('own', $order);
        return view('orders.show', ['order' => $order->load('items.product', 'items.productSku')]);
    }

    public function received(Order $order, Request $request)
    {
        // 检验权限
        $this->authorize('own', $order);

        // 判断订单的发货状态是否未已发货
        if ($order->ship_status !== Order::SHIP_STATUS_DELIVERED) {
            throw new InvalidRequestException('发货状态不正确');
        }

        // 更新发货状态为已收到
        $order->update(['ship_status' => Order::SHIP_STATUS_RECEIVED]);

        // 返回原页面
        return $order;
    }

    public function review(Order $order)
    {
        $this->authorize('own', $order);
        if (!$order->paid_at) {
            throw new InvalidRequestException('该订单未支付，不可评价');
        }
        return view('orders.review', ['order' => $order->load(['items.product', 'items.productSku'])]);
    }

    public function sendReview(Order $order, SendReviewRequest $request)
    {
        $this->authorize('own', $order);
        if (!$order->paid_at) {
            throw new InvalidRequestException('该订单未支付，不可评价');
        }

        if ($order->reviewed) {
            throw new InvalidRequestException('该订单已评价，不可重复提交');
        }

        $reviews = $request->input('reviews');
        \DB::transaction(function () use ($reviews, $order) {
            foreach ($reviews as $review) {
                $orderItem = $order->items()->find($review['id']);
                $orderItem->update([
                    'rating' => $review['rating'],
                    'review' => $review['review'],
                    'reviewed_at' => now(),
                ]);
            }

            $order->update([
                'reviewed' => true,
            ]);
            event(new OrderReviewed($order));
        });

        return redirect()->back();
    }

    public function applyRefund(Order $order, ApplyRefundRequest $request)
    {
        $this->authorize('own', $order);
        if (!$order->paid_at) {
            throw new InvalidRequestException('该订单未支付，不可退款');
        }

        if ($order->refund_status !== Order::REFUND_STATUS_PENDING) {
            throw new InvalidRequestException('该订单已申请过退款，请勿重新申请');
        }

        $extra = $order->extra ?: [];
        $extra['refund_reason'] = $request->input('reason');
        $order->update([
            'refund_status' => Order::REFUND_STATUS_APPLIED,
            'extra' => $extra,
        ]);

        return $order;
    }

    public function handRefund(Order $order, HandleRefundRequest $request)
    {
        if ($order->refund_status !== Order::REFUND_STATUS_APPLIED) {
            throw new InvalidRequestException('订单状态不正确');
        }

        if ($request->input('agree')) {
            $this->_refundOrder($order);
        } else {

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
    }
}
