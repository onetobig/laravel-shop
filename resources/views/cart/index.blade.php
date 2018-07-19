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
								<td><input type="checkbox" id="select-all"></td>
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
										<input type="checkbox" name="select" value="{{ $item->productSku->id }}" {{ $item->productSku->product->on_sale ? 'checked' : 'disabled' }}>
									</td>
									<td class="product_info">
										<div class="preview">
											<a href="{{ route('products.show', [$item->productSku->product->id]) }}" target="_blank">
												<img src="{{ $item->productSku->product->image_url }}">
											</a>
										</div>
										<div class="{{ $item->productSku->product->on_sale ? '' : 'not_on_sale'}}">
											<span class="product_title">
											<a href="{{ route('products.show', [$item->productSku->product->id]) }}" target="_blank">
												{{ $item->productSku->product->title }}
											</a>
											
											</span>
											<span class="sku_title">
												{{ $item->productSku->title }}
											</span>
											@if(!$item->productSku->product->on_sale)
												<span class="warning">该商品已下架</span>
											@endif
										</div>
									</td>
									<td><span class="price">￥{{ $item->productSku->price }}</span></td>
									<td>
										<input type="text" class="form-control input-sm amount" @if(!$item->productSku->product->on_sale) disabled @endif name="amount" value="{{ $item->amount }}">
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
									<div class="con-sm-9 col-md-7">
										<textarea name="remark" class="form-control" rows="3"></textarea>
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
	</div>
@endsection

@section('scriptAfterJs')
	<script>
		$(document).ready(function () {
		    $('.btn-remove').click(function () {
		        var id = $(this).closest('tr').data('id');
		        swal({
			        title: "确认要将该商品移除？",
			        icon: "warning",
			        buttons: ['取消', '确定'],
			        dangerMode: true,
		        }).then(function (willDelete) {
		            if (!willDelete) {
		                return;
		            }
		            axios.delete('/cart/' + id)
			            .then(function () {
				            location.reload();
                        })
                })
            });
		    
		    $('#select-all').change(function () {
			   var checked = $(this).prop('checked');
			   
			   $('input[name=select][type=checkbox]:not([disabled])').each(function () {
				   $(this).prop('checked', checked);
               });
            });
		    
		    $('.btn-create-order').click(function () {
			    var req = {
			        address_id: $('#order-form').find('select[name=address]').val(),
				    items: [],
				    remark: $('#order-form').find('textarea[name=remark]').val(),
			    };
			    $('table tr[data-id]').each(function () {
			        var $checkbox = $(this).find('input[name=select][type=checkbox]');
			        if ($checkbox.prop('disabled') || !$checkbox.prop('checked')) {
			            return;
			        }
			        var $input = $(this).find('input[name=amount]');
			        if (isNaN($input.val()) || parseInt($input.val()) === 0 ) {
			            return;
			        }
			        req.items.push({
				        sku_id: $(this).data('id'),
				        amount: $input.val(),
			        });
                });
			    axios.post('{{ route('orders.store') }}', req)
				    .then(function (response) {
					    swal('订单提交成功', '', 'success');
                    }, function (error) {
					    if (error.response.status === 422) {
					        var html = '<div>';
					        _.each(error.response.data.errors, function (errors) {
						        _.each(errors, function (error) {
							        html += error + '<br>';
                                })
                            });
					        html += '</div>';
					        swal({content: $(html)[0], icon:'error'});
					    } else {
					        swal('系统错误', '', 'error');
					    }
                    })
            });
        });
	</script>
@endsection
