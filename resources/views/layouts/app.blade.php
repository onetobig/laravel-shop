<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>@yield('title', config('app.name')) - Laravel 电商教程</title>
	<!-- 样式 -->
	<link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>
	<div id="app" class="{{ route_class() }}-page">
		@include('layouts._header')
		@yield('content')
		@include('layouts._footer')
	</div>
	<!-- JS 脚本 -->
	<script src="{{ mix('js/app.js') }}"></script>
	@yield('scriptAfterJs')
</body>
</html>
