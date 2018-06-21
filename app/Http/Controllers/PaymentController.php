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

    public function wechatRefundNotify(Request $request)
    {
        // 给微信的失败响应
        $failXml = '<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[FAIL]]></return_msg></xml>';
        // 把请求的 xml 内容解析成数组
        $input = parse_xml($request->getContent());
        // 如果解析失败或者没有必要的字段，则返回错误
        if (!$input || !isset($input['req_info'])) {
            return $failXml;
        }
        // 对请求中的 req_info 字段进行 base64 解码
        $encryptedXml = base64_decode($input['req_info'], true);
        // 对解码后的 req_info 字段进行 AES 解密
        $decryptedXml = openssl_decrypt($encryptedXml, 'AES-256-ECB', md5(config('pay.wechat.key')), OPENSSL_RAW_DATA, '');
        // 如果解密失败则返回错误
        if (!$decryptedXml) {
            return $failXml;
        }
        // 解析解密后的 xml
        $decryptedData = parse_xml($decryptedXml);
        // 没有找到对应的订单，原则上不可能发生，保证代码健壮性
        if(!$order = Order::where('no', $decryptedData['out_trade_no'])->first()) {
            return $failXml;
        }

        if ($decryptedData['refund_status'] === 'SUCCESS') {
            // 退款成功，将订单退款状态改成退款成功
            $order->update([
                'refund_status' => Order::REFUND_STATUS_SUCCESS,
            ]);
        } else {
            // 退款失败，将具体状态存入 extra 字段，并表退款状态改成失败
            $extra = $order->extra;
            $extra['refund_failed_code'] = $decryptedData['refund_status'];
            $order->update([
                'refund_status' => Order::REFUND_STATUS_FAILED,
            ]);
        }

        return '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
    }
}
