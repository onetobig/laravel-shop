@extends('layouts.app')
@section('title', '提示')
@section('content')
	<div class="container">
		<div class="panel panel-default">
			<div class="panel-heading">提示</div>
			<div class="panel-body text-center">
				<h1>请先验证邮箱</h1>
				<a href="{{ route('email_verification.send') }}" class="btn btn-primary">重新发送验证邮箱</a>
			</div>
		</div>
	</div>
@endsection
