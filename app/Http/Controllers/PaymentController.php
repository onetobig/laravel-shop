<?php

namespace App\Http\Controllers;

use App\Events\OrderPaid;
use App\Exceptions\InvalidRequestException;
use App\Models\Order;
use Endroid\QrCode\QrCode;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function payByAlipay(Order $order, Request $request)
    {
        $this->authorize('own', $order);

        if ($order->paid_at || $order->closed) {
            throw new InvalidRequestException('订单状态不正确');
        }

        return app('alipay')->web([
            'out_trade_no' => $order->no,
            'total_amount' => $order->total_amount,
            'subject' => '支付 Laravel Shod 的订单：' . $order->no,
        ]);
    }

    // 前端回调
    public function alipayReturn()
    {
        try {
            $data = app('alipay')->verify();
        } catch (\Exception $e) {
            return view('pages.error', [['msg' => '数据不正确']]);
        }

        return view('pages.success', ['msg' => '付款成功']);
    }

    // 服务器端回调
    public function alipayNotify()
    {
        $data = app('alipay')->verify();

        $order = Order::where('no', $data->out_trade_no)->first();
        if(!$order) {
            return 'fail';
        }

        if($order->paid_at) {
            return app('alipay')->success();
        }

        $order->update([
            'paid_at' => now(),
            'payment_method' => 'alipay',
            'payment_no' => $data->trade_no,
        ]);
        $this->afterPaid($order);
        return app('alipay')->success();
    }

    public function payByWechat(Order $order, Request $request)
    {
        $this->authorize('own', $order);

        if($order->paid || $order->closed) {
            throw new InvalidRequestException('订单状态不正确');
        }

        $wechatOrder = app('wechat_pay')->scan([
            'out_trade_no' => $order->no,
            'total_fee' => $order->total_amount * 100,
            'body' => '支付 Laravel Shop 的订单：' . $order->no,
        ]);

        $qrCode = new QrCode($wechatOrder->code_url);

        return response($qrCode->writeString(), 200, ['Content-Type' => $qrCode->getContentType()]);
    }

    public function wechatNotify()
    {
        $data = app('wechat_pay')->verify();
        $order = Order::where('no', $data->ouot_trade_no)->first();
        if(!$order) {
            return 'fail';
        }

        if($order->paid_at) {
            return app('wechat_pay')->success();
        }

        $order->update([
            'paid_at' => now(),
            'payment_method' => 'alipay',
            'payment_no' => $data->trade_no,
        ]);
        $this->afterPaid($order);
        return app('wechat_pay');
    }

    public function afterPaid(Order $order)
    {
        event(new OrderPaid($order));
    }
}
