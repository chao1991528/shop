<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Exceptions\CouponCodeUnavailableException;
use Carbon\Carbon;

class CouponCode extends Model
{
    // 用常量的方式定义支持的优惠券类型
    const TYPE_FIXED = 'fixed';
    const TYPE_PERCENT = 'percent';

    public static $typeMap = [
        self::TYPE_FIXED   => '固定金额',
        self::TYPE_PERCENT => '比例',
    ];

    protected $fillable = [
        'name',
        'code',
        'type',
        'value',
        'total',
        'used',
        'min_amount',
        'not_before',
        'not_after',
        'enabled',
    ];
    protected $casts = [
        'enabled' => 'boolean',
    ];
    // 指明这两个字段是日期类型
    protected $dates = ['not_before', 'not_after'];

    protected $appends = ['description'];

    public static function findAvailableCode($length = 16)
    {
    	do {
    		//随机生成一个字符串并转化为大写
    		$code = strtoupper(Str::random($length));
    	} while (self::query()->where('code', $code)->exists());

    	return $code; 
    }

    public function getDescriptionAttribute()
    {
    	$str = '';
    	if($this->min_amount > 0){
    		$str .= '满' . $this->min_amount;
    	}
    	if($this->type == self::TYPE_PERCENT){
    		$str .= '优惠' . $this->value . '%';
    	}
    	if($this->type == self::TYPE_FIXED){
    		$str .= '减免' . $this->value;
    	}
    	return $str;
    }

    public function checkAvailable($orderAmount=null)
    {
    	if(!$this->enabled){
    		throw new CouponCodeUnavailableException("优惠券不存在");
    	}
    	if($this->used >= $this->total){
    		throw new CouponCodeUnavailableException("该优惠券已被兑完");
    	}
    	if($this->not_before && $this->not_before->gt(Carbon::now())){
    		throw new CouponCodeUnavailableException("该优惠现在不能使用");
    	}
    	if($this->not_after && $this->not_after->lt(Carbon::now())){
    		throw new CouponCodeUnavailableException("该优惠券已过期");
    	}
    	if (!is_null($orderAmount) && $orderAmount < $this->min_amount) {
            throw new CouponCodeUnavailableException('订单金额不满足该优惠券最低金额');
        }
    }

    public function getAdjustedPrice($orderAmount){
    	if($this->type == self::TYPE_FIXED){
    		return max(0.041, $orderAmount - $this->value);
    	} else {
    		if(($this->min_amount > 0) && ($orderAmount >= $this->min_amount)){
    			return number_format($orderAmount * (100 - $this->value) / 100, 2, '.', '');
    		} 
    	}
    	return $orderAmount;
    }

    public function changeUsed($increase = true)
    {
    	 // 传入 true 代表新增用量，否则是减少用量
    	if($increase){
    		return $this->newQuery()->where('id', $this->id)->where('used', '<', $this->total)->increment('used');
    	} else {
    		return $this->decrement('used');
    	}
    } 
}
