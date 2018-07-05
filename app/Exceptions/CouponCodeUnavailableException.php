<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Exception;

class CouponCodeUnavailableException extends Exception
{
    public function __construct($message, int $code=403)
    {
    	parent::__construct($message, $code);
    }

    public function render(Request $request)
    {
    	//如果是api请求
    	if($request->expectsJson()){
    		return response()->json(['msg' => $this->message, 'code' => $this->code]);
    	}

    	return redirect()->back()->withErrors(['coupon_code' => $this->message]);
    }
}
