@extends('layouts.app')
@section('title', '商品列表')
@section('content')
	<div class="container">
		<div class="row">
			<div class="col-lg-10 col-lg-offset-1">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="row">
							<form action="{{ route('products.index') }}" class="form-inline search-form">
								<input type="hidden" name="filters">
								<a href="{{ route('products.index') }}" class="all-products">全部</a> &gt;
								@if ($category)
									@foreach($category->ancestors as $ancestor)
										<span class="category">
											<a href="{{ route('products.index', ['category_id' => $ancestor->id]) }}">{{ $ancestor->name }}</a>
										</span>
										<span>></span>
									@endforeach
									<span class="category">{{ $category->name }}<span> ></span></span>
										<input type="hidden" name="category_id" value="{{ $category->id }}">
								@endif
								
								{{--商品属性面包屑开始--}}
								@foreach($propertyFilters as $name => $value)
									<span class="filter">{{ $name }}:
										<span class="filter-value">{{ $value }}</span>
										<a href="javascript: removeFilterFromQuery('{{ $name }}')" class="remove-filter">x</a>
									</span>
								@endforeach
								{{--商品属性面包屑结束--}}
								<input type="text" class="form-control input-sm" name="search" placeholder="搜索">
								<button class="btn btn-primary btn-sm">搜索</button>
								<select name="order" id="" class="form-control input-sm pull-right">
									<option value="">排序方式</option>
									<option value="price_asc">价格从低到高</option>
									<option value="price_desc">价格从高到低</option>
									<option value="sold_count_desc">销量从高到低</option>
									<option value="sold_count_asc">销量从低到高</option>
									<option value="rating_asc">评价量从低到高</option>
									<option value="rating_desc">评价从高到低</option>
								</select>
							</form>
						</div>
						
						{{--展示子类目--}}
						<div class="filters">
							@if($category && $category->is_directory)
								<div class="row">
									<div class="col-xs-3 filter-key">子类目：</div>
									<div class="col-xs-9 filter-values">
										@foreach($category->children as $child)
											<a href="{{ route("products.index", ['category_id' => $child->id]) }}">{{ $child->name }}</a>
										@endforeach
									</div>
								</div>
							@endif
							{{--分面搜索结果开始--}}
							{{--遍历聚合属性--}}
							@foreach($properties as $property)
								<div class="row">
									<div class="col-xs-3 filter-key">{{ $property['key'] }}</div>
									<div class="col-xs-9 filter-values">
										@foreach($property['values'] as $value)
											<a href="javascript: appendFilterToQuery('{{ $property['key'] }}', '{{ $value }}');">{{ $value }}</a>
										@endforeach
									</div>
								</div>
							@endforeach
							{{--分面搜索结果结束--}}
						</div>
						
						
						<div class="row products-list">
							@foreach($products as $product)
								<div class="col-xs-3 product-item">
									<div class="product-content">
										<div class="top">
											<a href="{{ route('products.show', [$product->id]) }}" target="_blank">
												<div class="img"><img src="{{ $product->image_url }}" alt=""></div>
											</a>
											<div class="price"><b>￥</b>{{ $product->price }}</div>
											<a href="{{ route('products.show', [$product->id]) }}" target="_blank">
												<div class="title">{{ $product->title }}</div>
											</a>
										</div>
										<div class="bottom">
											<div class="sold_count">销量 <span>{{ $product->sold_count }}笔</span></div>
											<div class="review_count">评价 <span>{{ $product->sold_count }}</span></div>
										</div>
									</div>
								</div>
							@endforeach
						</div>
						<div class="pull-right">{{ $products->appends($filters)->render() }}</div>
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection

@section('scriptAfterJs')
	<script>
		let filters = {!! json_encode($filters) !!}
		
		$(document).ready(function () {
		    let filters = {!! json_encode($filters) !!}
			$('.search-form input[name=search]').val(filters.search);
            $('.search-form select[name=order]').val(filters.order);
        });
		
		$('.search-form select[name=order]').on('change', function () {
		    let searches = parseSearch();
		    if (searches['filters']) {
		        $('.search-form input[name=filters]').val(searches['filters']);
		    }
		    $('.search-form').submit();
        });
		
		// 解析当前的 url 里面的参数，并以 Key-Value 对象形式返回
		function parseSearch() {
		    let searches = {};
		    
		    location.search.substr(1).split('&').forEach(function (str) {
		        let result = str.split('=');
		        searches[decodeURIComponent(result[0])] = decodeURIComponent(result[1])
		    });
			
			return searches;
		}
		
		// 根据 Key-Value 对象构建查询
		function buildSearch(searches) {
		    // 初始化字符串
		    let query = '?';
			_.forEach(searches, function (value, key) {
			    query += encodeURIComponent(key) + '=' + encodeURIComponent(value) + '&';
            });
			// 驱虎末尾的 & 符号
			return query.substr(0, query.length - 1);
		}
		
		// 将新的 filter 追加到当前的 url 中
		function appendFilterToQuery(name, value) {
		    let searches = parseSearch();
			if (searches['filters']) {
		        searches['filters'] += '|' + name + ':' +value;
			} else {
		        searches['filters'] = name + ':' + value
			}
			location.search = buildSearch(searches)
		}
		
		function removeFilterFromQuery(name) {
		    let searches = parseSearch();
		    if (!searches['filters']) {
		        return;
		    }
		    
		    let filters = [];
		    searches['filters'].split('|').forEach(function (filter) {
			    let result = filter.split(':');
			    if (result[0] === name) {
			        return;
			    }
			    filters.push(filter);
            });
		    searches['filters'] = filters.join('|');
			location.search = buildSearch(searches);
		}
	</script>
@endsection
