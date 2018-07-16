@extends('layouts.app')
@section('title', '购物车')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-lg-10 col-lg-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">我的购物车</div>
				<div class="panel-body">
					<table class="table table-striped">
						<thead>
						<tr>
							<th><input type="checkbox" id="select-all"></th>
							<th>商品信息</th>
							<th>单价</th>
							<th>数量</th>
							<th>操作</th>
						</tr>
						</thead>
						<tbody class="product_list">
						@foreach($cartItems as $item)
							<tr data-id="{{ $item->productSku->id }}">
								<td>
									<input type="checkbox" name="select" value="{{ $item->productSku->id }}" {{ $item->productSku->product->on_sale ? 'checked' : 'disable' }}>
								</td>
								<td class="product_info">
									<div class="preview">
										<a href="{{ route('products.show', [$item->productSku->product->id]) }}" target="_blank">
											<img src="{{ $item->productSku->product->image_url }}">
										</a>
									</div>
									<div @if(!$item->productSku->product->on_sale) class="not_on_sale" @endif>
										<span class="product_title">
										<a href="{{ route('products.show', [$item->productSku->product->id]) }}" target="_blank">
											{{ $item->productSku->product->title }}
										</a>
										</span>
										<span class="sku_title">{{ $item->productSku->title }}</span>
										@if(!$item->productSku->product->on_sale)
											<span class="waring">该商品已下架</span>
										@endif
									</div>
								</td>
								<td><span class="price">￥{{ $item->productSku->price }}</span></td>
								<td>
									<input type="text" class="form-control input-sm amount" @if(!$item->productSku->product->on_sale) disabled @endif name="amount" value="{{ $item->amount }}" >
								</td>
								<td>
									<button class="btn btn-xs btn-danger btn-remove">移除</button>
								</td>
							</tr>
						@endforeach
						
						</tbody>
					</table>
					
					<div>
						<form class="form-horizontal" role="form" id="order-form">
							<div class="form-group">
								<label class="control-label col-sm-3">选择收货地址</label>
								<div class="col-sm-9 col-md-7">
									<select name="address" class="form-control">
										@foreach($addresses as $address)
											<option value="{{ $address->id }}">{{ $address->full_address }} {{ $address->contact_name }} {{ $address->contact_name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-3">备注</label>
								<div class="col-sm-9 col-md-7">
									<textarea name="remark" class="form-control" rows="3"></textarea>
								</div>
							</div>
							<!-- 优惠码开始 -->
							<div class="form-group">
								<label class="control-label col-sm-3">优惠码</label>
								<div class="col-sm-4">
									<input type="text" class="form-control" name="coupon_code">
									<span class="help-block" id="coupon_desc"></span>
								</div>
								<div class="col-sm-3">
									<button type="button" class="btn btn-success" id="btn-check-coupon">检查</button>
									<button type="button" class="btn btn-danger" id="btn-cancel-coupon" style="display: none;">取消</button>
								</div>
							</div>
							<!-- 优惠码结束 -->
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-3">
									<button class="btn btn-primary btn-create-order" type="button">提交订单</button>
								</div>
							</div>
						</form>
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
	        $('.btn-remove').click(function () {
                // $(this) 可以获取到当前点击的 移除 按钮的 jQuery 对象
                // closest() 方法可以获取到匹配选择器的第一个祖先元素，在这里就是当前点击的 移除 按钮之上的 <tr> 标签
                // data('id') 方法可以获取到我们之前设置的 data-id 属性的值，也就是对应的 SKU id
                var id = $(this).closest('tr').data('id');
                swal({
	                title: "确认要将该商品移除？",
	                icon: "warning",
	                buttons: ['取消', '确定'],
	                dangerMode: true,
                })
	                .then(function (willDelete) {
		                if (!willDelete) {
		                    return;
		                }
		                axios.delete('/cart/' + id)
			                .then(function () {
				                location.herf = '{{ route('cart.index') }}';
                            })
                    })
            });
	        
	        $("#select-all").change(function () {
                // 获取单选框的选中状态
                // prop() 方法可以知道标签中是否包含某个属性，当单选框被勾选时，对应的标签就会新增一个 checked 的属性
                var checked = $(this).prop('checked');
                // 获取所有 name=select 并且不带有 disabled 属性的勾选框
		        $('input[name=select][type=checkbox]:not([disabled])').each(function () {
		            $(this).prop('checked', checked);
                });
            });
	        
	        $('.btn-create-order').click(function () {
	            var req = {
	                address_id: $("#order-form").find('select[name=address]').val(),
		            items: [],
		            remark: $('#order-form').find('textarea[name=remark]').val(),
	            };
                // 遍历 <table> 标签内所有带有 data-id 属性的 <tr> 标签，也就是每一个购物车中的商品 SKU
	            $('table tr[data-id]').each(function () {
		            var $checkbox = $(this).find('input[name=select][type=checkbox]');
		            if ($checkbox.prop('disabled') || !$checkbox.prop('checked')) {
		                return;
		            }
		            var $input = $(this).find('input[name=amount]');
		            if ($input.val() === 0 || isNaN($input.val())) {
		                return;
		            }
		            req.items.push({
			            sku_id: $(this).data('id'),
			            amount: $input.val(),
		            });
                });
	            
	            axios.post('{{ route('orders.store') }}', req)
		            .then(function (response) {
			            swal('订单提交成功', '', 'success')
				            .then(() => {
				                location.href = '/orders/' + response.data.id;
				            })
                    }, function (error) {
			            if (error.response.status === 422) {
			                helper.swalError(error.response.data.errors);
			            } else {
			                helper.systemError();
			            }
                    })
	        });
	        
	        $('#btn-check-coupon').click(function () {
		        var code = $('input[name=coupon_code]').val();
		        if (!code) {
		            swal('请输入优惠码', '', 'warning');
		            return;
		        }
		        
		        axios.get('/coupon_codes/' + encodeURIComponent(code))
			        .then(function (response) {
				        $('#coupon_desc').text(response.data.description);
				        $('input[name=coupon_code]').prop('readonly', true);
				        $('#btn-cancel-coupon').show();
				        $('#btn-check-coupon').hide();
                    }, function (error) {
				        if (error.response.status === 404) {
				            swal('优惠码不存在', '', 'error');
				        } else if (error.response.status === 403) {
				            swal(error.response.data.msg, '', 'error');
				        }else {
				            helper.systemError();
				        }
                    });
		        
		        // 隐藏 按钮点击事件
		        $('#btn-cancel-coupon').click(function () {
			        $('#coupon_desc').text('');
			        $('input[name=coupon_code]').prop('readonly', false);
			        $('#btn-cancel-coupon').hide();
			        $('#btn-check-coupon').show();
                })
            })
        });
	</script>
@endsection
