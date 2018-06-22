@extends('layouts.app')
@section('title', '购物车')
@section('content')
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
									<input type="checkbox" name="select"
									       value="{{ $item->productSku->id }}" {{ $item->productSku->product->on_sale ? 'checked' : 'disabled' }}>
								</td>
								<td class="product_info">
									<div class="preview">
										<a target="_blank"
										   href="{{ route('products.show', [$item->productSku->product_id]) }}">
											<img src="{{ $item->productSku->product->image_url }}" class="">
										</a>
									</div>
									<div @if(!$item->productSku->product->on_sale) class="not_on_sale" @endif>
										<span class="product_title">
											<a target="_blank" href="{{ route('products.show', [$item->productSku->product_id]) }}">{{ $item->productSku->product->title }}</a>
										</span>
										<span class="sku_title">{{ $item->productSku->title }}</span>
										@if(!$item->productSku->product->on_sale)
											<span class="warning">该商品已下架</span>
									    @endif
								</td>
								<td><span class="price">￥{{ $item->productSku->price }}</span></td>
								<td>
									<input type="text" class="form-control input-sm amount"
									       @if(!$item->productSku->product->on_sale) disabled @endif name="amount"
									       value="{{ $item->amount }}">
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
											<option value="{{ $address->id }}">{{ $address->full_address }} {{ $address->contact_name }} {{ $address->contact_phone }}</option>
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
							
							<div class="form-group">
								<label class="control-label col-sm-3">优惠码</label>
								<div class="col-sm-4">
									<input type="text" class="form-control" name="coupon_code" />
									<span class="help-block" id="coupon_desc"></span>
								</div>
								<div class="col-sm-3">
									<button class="btn btn-success" type="button" id="btn-check-coupon">检查</button>
									<button class="btn btn-danger" id="btn-cancel-coupon" type="button" style="display: none;">取消</button>
								</div>
							</div>
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
@endsection
@section('scriptAfterJs')
	<script>
        $(document).ready(function (){
            $('.btn-remove').click(function () {
	            var id = $(this).closest('tr').data('id');
	            swal({
		            title: '确定要讲该商品移除？',
		            icon: 'warning',
		            buttons: ['取消', '确定'],
		            dangerMode: true,
	            })
		            .then(function(willDelete) {
		                if (!willDelete) {
		                    return;
		                }
		                axios.delete('/cart/' +id)
			                .then(function () {
			                    location.reload();
			                })
		            })
            })
        });
        
        //  全选功能
		$('#select-all').change(function () {
		    var checked = $(this).prop('checked')
			
			$('input[name=select][type=checkbox]:not([disabled])').each(function() {
			    $(this).prop('checked', checked)
			})
		})
		
		// 监听创建订单按钮的点击事件
		$('.btn-create-order').click(function() {
		    var req = {
		        address_id: $('#order-form').find('select[name=address]').val(),
			    items: [],
			    remark: $('#order-form').find('textarea[name=remark]').val(),
			    coupon_code: $('input[name=coupon_code]').val(),
		    };
		    
		    // 遍历 <table> 标签内所有带有 data-id 属性的 <tr> 标签，也就是每一个购物车中的商品 SKU
			$('table tr[data-id]').each(function () {
                // 获取当前行的单选框
			    var $checkbox = $(this).find('input[name=select][type=checkbox]');
                // 如果单选框被禁用或者没有被选中则跳过
			    if ($checkbox.prop('disabled') || !$checkbox.prop('checked')) {
			        return;
			    }
                // 获取当前行中数量输入框
			    var $input = $(this).find('input[name=amount]');
			    // 如果用户将数量设为 0 或者不是一个数字，则也跳过
				if ($input.val() == 0 || isNaN($input.val())) {
				    return;
				}
				
				req.items.push({
					sku_id: $(this).data('id'),
					amount: $input.val(),
				})
			});
			
			axios.post('{{ route('orders.store') }}', req)
				.then(function (response){
                    location.href = '/orders/' + response.data.id;
				}, function (error) {
				    if(error.response.status === 422) {
                        var html = '<div>';
                        _.each(error.response.data.errors, function (errors) {
                            _.each(errors, function (error) {
                                html += error + '<br>';
                            });
                        });
                        html += '</div>';
                        swal({content: $(html)[0], icon: 'error'})
				    }else {
				        swal('系统错误', '', 'error');
				    }
				    return false;
                })
		})
		
		// 检查按钮点击事件
		$('#btn-check-coupon').click(function () {
			var code = $('input[name=coupon_code]').val();
			if (!code) {
			    swal('请输入优惠码', '', 'warning');
				return;
			}
			// 调用检查接口
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
				        swal(error.response.data.msg,  '', 'error');
				    } else {
				        swal('系统内部错误', '', 'error');
				    }
                })
			
			$('#btn-cancel-coupon').click(function () {
			    $('#coupon_desc').text('');
			    $('input[name=coupon_code]').prop('readonly', false);
			    $('#btn-cancel-coupon').hide();
                $('#btn-check-coupon').show();
			});
        })
	</script>
@endsection