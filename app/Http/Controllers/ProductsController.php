<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $builder = Product::query()->where('on_sale', true);

        if ($search = $request->input('search', '')) {
            $like = '%' . $search . '%';
            $builder->where(function ($query) use ($like)  {
                $query->where('title', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhereHas('skus', function($query) use ($like) {
                        $query->where('title', 'like', $like)
                            ->orWhere('description', 'like', $like);
                    });
            });
        }

        if($order = $request->input('order', ''))  {
            if( preg_match('/^(.+)_(asc|desc)$/', $order, $m)) {
                if ( in_array($m[1], ['price', 'sold_count', 'rating'])) {
                    $builder->orderBy($m[1], $m[2]);
                }
            }
        }
        $products = $builder->paginate(16);

        $filters = compact('search', 'order');
        return view('products.index', compact('products', 'filters'));
    }
}
