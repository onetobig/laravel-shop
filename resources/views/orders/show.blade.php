@extends('layouts.app')
@section('title', '查看订单')
@section('content')
	<div class="container">
		<div class="row">
			<div class="col-lg-10 col-lg-offset-1">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4>订单详情</h4>
					</div>
					<div class="panel-body">
						<table class="table">
							<thead>
							<tr>
								<th>商品信息</th>
								<th class="text-center">单价</th>
								<th class="text-center">数量</th>
								<th class="text-right text-center">小计</th>
							</tr>
							</thead>
							<tbody>
							@foreach($order->items as $index => $item)
								<tr>
									<td class="product-info">
										<div class="preview">
											<a href="{{ route('products.show', [$item->product_id]) }}">
												<img src="{{ $item->product->image_url }}">
											</a>
										</div>
										<div>
											<span class="product-title">
												<a href="{{ route('products.show', [$item->product_id]) }}">{{ $item->product->title }}</a>
											</span>
											<span class="sku-title">{{ $item->productSku->title }}</span>
										</div>
									</td>
									<td class="sku-price text-center vertical-middle">￥{{ $item->price }}</td>
									<td class="sku-amount text-center vertical-middle">{{ $item->amount }}</td>
									<td class="item-price text-right vertical-middle">￥{{ number_format($item->price * $item->amount, 2, '.', '') }}</td>
								</tr>
							@endforeach
							<tr><td colspan="4"></td></tr>
							</tbody>
						</table>
						<div class="order-bottom">
							<div class="order-info">
								<div class="line"><div class="line-label">收货地址：</div><div class="line-value">{{ join(' ', $order->address) }}</div></div>
								<div class="line"><div class="line-label">订单备注：</div><div class="line-value">{{ $order->remark ?: '-' }}</div></div>
								<div class="line"><div class="line-label">订单编号：</div><div class="line-value">{{ $order->no }}</div></div>
								
								<div class="line">
									<div class="line-label">物流状态：</div>
									<div class="line-value">{{ \App\Models\Order::$shipStatusMap[$order->ship_status] }}</div>
								</div>
								
								@if($order->ship_data)
									<div class="line">
										<div class="line-label">物流信息：</div>
										<div class="line-value">{{ implode(' ', $order->ship_data) }}</div>
									</div>
								@endif
								
								@if($order->paid_at && $order->refund_status !== \App\Models\Order::REFUND_STATUS_PENDING)
									<div class="line">
										<div class="line-label">退款状态：</div>
										<div class="line-value">{{ \App\Models\Order::$refundStatusMap[$order->refund_status] }}</div>
									</div>
									<div class="line">
										<div class="line-label">退款理由：</div>
										<div class="line-value">{{ $order->extra['refund_reason'] }}</div>
									</div>
								@endif
							</div>
							<div class="order-summary text-right">
								<div class="total-amount">
									<span>订单总价：</span>
									<div class="value">￥{{ $order->total_amount }}</div>
								</div>
								<div>
									<span>订单状态：</span>
									<div class="value">
										@if($order->paid_at)
											@if($order->refund_status === \App\Models\Order::REFUND_STATUS_PENDING)
												已支付
											@else
												{{ \App\Models\Order::$refundStatusMap[$order->refund_status] }}
											@endif
										@elseif($order->closed)
											已关闭
										@else
											未支付
										@endif
									</div>
								</div>
								@if(!$order->paid_at && !$order->closed)
									<div class="payment-buttons">
										<a href="{{ route('payment.alipay', [$order->id]) }}" class="btn btn-primary btn-sm">支付宝支付</a>
									</div>
								@endif
								
								@if($order->ship_status === \App\Models\Order::SHIP_STATUS_DELIVERED)
									<div class="receive-button">
										<button class="btn btn-sm btn-success" type="button" id="btn-receive">确认收货</button>
									</div>
								@endif
								
								@if($order->paid_at && $order->refund_status === \App\Models\Order::REFUND_STATUS_PENDING)
									<div class="refund-button">
										<button class="btn btn-sm btn-danger" id="btn-apply-refund">申请退款</button>
									</div>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('script')
	<script>
		$(document).ready(function () {
		   $('#btn-receive').click(function () {
		       swal({
			       title: "确认已经收到商品？",
			       icon: "warning",
			       dangerMode: true,
			       buttons: ['取消', '确认收到']
			       
		       })
			       .then(function (ret) {
				       if (!ret) {
				           return;
				       }
				       axios.post('{{ route('orders.received', [$order->id]) }}')
					       .then(function () {
						       location.reload();
                           });
                   });
		   });
		   
		   $('#btn-apply-refund').click(function () {
			   swal({
				   text: '请输入退款理由',
				   content: 'input',
			   }).then(function (input) {
                   if(!input) {
                       swal('退款理由不可为空', '', 'error')
	                   return;
                   }
                   axios.post('{{ route('orders.apply_refund', [$order->id]) }}', {reason:input})
	                   .then(function () {
	                       swal('申请退款成功', '',  'success').then(function () {
	                           location.reload();
                           });
                       }, function (error) {
	                       if (error.response.data.msg) {
	                           swal(error.response.data.msg, '', 'error');
	                       }
                       });
               });
           })
		});
	</script>
@endsection


