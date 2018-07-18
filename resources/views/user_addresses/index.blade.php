@extends('layouts.app')
@section('title', '收货地址列表')
@section('content')
	<div class="container">
		<div class="row">
			<div class="col-lg-10 col-lg-offset-1">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="container-fluid">
							收货地址列表
							<a href="{{ route('user_addresses.create') }}" class="pull-right btn btn-primary">新增收货地址</a>
						</div>
					</div>
					<div class="panel-body">
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
											<a href="{{ route('user_addresses.edit', [$address->id]) }}"
											   class="btn btn-primary">修改</a>
											<button class="btn btn-danger btn-del-address" data-id="{{ $address->id }}">
												删除
											</button>
										</td>
									</tr>
								@endforeach
								</tbody>
							</table>
							<div class="pull-right">
								{{ $addresses->links() }}
							</div>
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
            $('.btn-del-address').click(function () {
                var id = $(this).data('id');

                swal({
                    title: '确认要删除该地址？',
                    icon: "warning",
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
            })
        })
	</script>
@endsection