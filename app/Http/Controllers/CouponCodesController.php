<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CouponCode;
use Carbon\Carbon;

class CouponCodesController extends Controller
{
    //优惠券检查
    public function show($code){
    	$couponCode = CouponCode::where('code', $code)->first();
    	if(!$couponCode){
    		abort(404);
    	}
    	if(!$couponCode->enabled){
    		abort(404);
    	}
    	if($couponCode->used >= $couponCode->total){
    		return response()->json(['msg' => '该优惠券已被兑完'], 403);
    	}
    	if($couponCode->not_before && $couponCode->not_before->gt(Carbon::now())){
    		return response()->json(['msg' => '该优惠现在不能使用'], 403);
    	}
    	if($couponCode->not_after && $couponCode->not_after->lt(Carbon::now())){
    		return response()->json(['msg' => '该优惠券已过期'], 403);
    	}

    	return $couponCode;
    }
}
