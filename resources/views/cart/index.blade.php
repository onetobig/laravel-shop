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
        });
	</script>
@endsection
