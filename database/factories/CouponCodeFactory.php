<?php

use Faker\Generator as Faker;
use App\Models\CouponCode;

$factory->define(CouponCode::class, function (Faker $faker) {
	//首先随机获取一个类型
    $type = $faker->randomElement(array_keys(CouponCode::$typeMap));
   	if($type === CouponCode::TYPE_FIXED){
   		//如果是固定金额，则最少金额必须比优惠金额多0.1
   		$value = random_int(1,50);
   		$min_amount = $value + 0.1;
   	}else{
   		// 如果是百分比折扣，有 50% 概率不需要最低订单金额
   		$value = random_int(1, 100);
   		if($value > 50){
   			$min_amount = random_int(100, 1000);
   		} else {
   			$min_amount = 0;
   		}
   	}
    return [
    	'name' => join(' ', $faker->words),// 随机生成名称
    	'code' => CouponCode::findAvailableCode(),
    	'type' => $type,
    	'value' => $value,
    	'total' => 1000,
    	'used' => 0,
    	'min_amount' => $min_amount,
    	'not_before' => null,
    	'not_after' => null,
    	'enabled' => true
    ];
});
