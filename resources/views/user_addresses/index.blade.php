@extends('layouts.app')
@section('title', '收货地址列表')
@section('content')
	<div class="row">
		<div class="col-lg-10 col-lg-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">收货地址列表</div>
				<div class="panel-body">
					<table class="table table-bordered table-striped">
						<thead>
						<tr>
							<th>收货人</th>
							<th>地址</th>
							<th>邮编</th>
							<th>电话</th>
							<th>操作</th>
						</tr>
						</thead>
						<tbody>
						@foreach($addresses as $address)
							<tr>
								<td>{{ $address->contact_name }}</td>
								<td>{{ $address->full_address }}</td>
								<td>{{ $address->zip }}</td>
								<td>{{ $address->contact_phone }}</td>
								<td>
									<a class="btn btn-primary"
									   href="{{ route('user_addresses.edit', ['user_address' =>  $address->id]) }}">修改</a>
									<button type="button" data-id="{{ $address->id }}"
									        class="btn btn-danger btn-del-address">删除
									</button>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('scriptAfterJs')
	<script>
        $(document).ready(function () {
            // 删除按钮点击事件
            $(".btn-del-address").click(function () {
                var id = $(this).data('id');
                swal({
                    title: '确定要删除改地址？',
                    icon: 'warning',
                    buttons: ['取消', '确定'],
                    dangerMode: true,
                }).then(function (willDelete) {
                    if (!willDelete) {
                        return;
                    }
                    axios.delete('/user_addresses/' + id)
                        .then(function () {
                            location.reload();
                        })
                });
            });


        })
	</script>
@endsection
