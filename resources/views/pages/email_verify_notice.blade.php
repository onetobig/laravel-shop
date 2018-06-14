@extends('layouts.app')
@section('title', '')
@section('content')
	<div class="panel panel-default">
		<div class="panel-heading">提示</div>
		<div class="panel-body container">
			<h1>请先验证邮箱</h1>
			<a href="{{ route('root') }}" class="btn btn-primary">返回首页</a>
		</div>
	</div>
@endsection
