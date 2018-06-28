<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductsController extends Controller
{
    //
    public function index(Request $request)
    {
    	//创建查询构造器
    	$builder = Product::query()->where('on_sale', true);
    	//seach参数
    	if($search = $request->input('search', '')){
    		$like = '%'.$search.'%';
    		//模糊搜索商品标题、商品详情、SKU 标题、SKU描述
    		$builder->where(function($query) use ($like){
    			$query->where('title', 'like', $like)
    				  ->orWhere('description', 'like', $like)
    				  ->orWhereHas('skus', function($query) use ($like){
    				  	$query->where('title', 'like', $like)
    				  		  ->orWhere('description', 'like', $like);	
    				  });
    		});
    	}
    	//order参数
    	if($order = $request->input('order', '')){
    		// 是否是以 _asc 或者 _desc 结尾
            if (preg_match('/^(.+)_(asc|desc)$/', $order, $m)) {
                // 如果字符串的开头是这 3 个字符串之一，说明是一个合法的排序值
                if (in_array($m[1], ['price', 'sold_count', 'rating'])) {
                    // 根据传入的排序值来构造排序参数
                    $builder->orderBy($m[1], $m[2]);
                }
            }
    	}
    	$products = $builder->paginate(16);

    	return view('products.index', [
    		'products' => $products, 
    		'filters' => [
    			'search' => $search, 
    			'order' => $order
    			]
    		]);
    }

    //商品详情
    public function show(Product $product, Request $request)
    {
    	if(!$product->on_sale){
    		throw new InvalidRequestException("商品未上架");
    	}

    	$favored = false;
    	if($user = $request->user()){
    		// 从当前用户已收藏的商品中搜索 id 为当前商品 id 的商品
            // boolval() 函数用于把值转为布尔值
            $favored = boolval($user->favoriteProducts()->find($product->id));
    	}

    	return view('products.show', ['product' => $product, 'favored' => $favored]);
    }

    public function favor(Product $product, Request $request)
    {
    	$user = $request->user();
    	if($user->favoriteProducts()->find($product->id)){
    		return [];
    	}

    	$user->favoriteProducts()->attach($product);
    	return [];
    }

    public function disfavor(Product $product, Request $request)
    {
    	$user = $request->user();
    	$user->favoriteProducts()->detach($product);

    	return [];
    }

    //用户收藏商品
    public function favorites(Request $request)
    {
    	$products = $request->user()->favoriteProducts()->paginate(16);

    	return view('products.favorites', ['products' => $products]);
    }
}
