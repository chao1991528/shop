<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
    	'title','description','image','on_sale','rating','sold_count','review_count','price'
    ]; 

    protected $casts = [
    	'on_sale' => 'boolean'
    ];
    //与商品sku关联
    public function skus()
    {
    	return $this->hasMany(ProductSku::class);
    }

    //商品图片路由
    public function getImageUrlAttribute()
    {
        return '/upload/'.$this->attributes['image'];
    }

}
