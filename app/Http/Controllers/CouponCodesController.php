<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CouponCode;
use Carbon\Carbon;
use App\Exceptions\CouponCodeUnavailableException;

class CouponCodesController extends Controller
{
    //优惠券检查
    public function show($code, Request $request){
    	$couponCode = CouponCode::where('code', $code)->first();
    	if(!$couponCode){
    		throw new CouponCodeUnavailableException("优惠券不存在", 404);
    	}

    	$couponCode->checkAvailable($request->user());

    	return $couponCode;
    }
}
