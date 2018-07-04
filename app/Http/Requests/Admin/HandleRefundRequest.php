<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class HandleRefundRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'agree'  => ['required', 'boolean'],
            'reason' => ['required_if:agree, false']
        ];
    }

    public function attributes()
    {
        return [
            'agree'  => '是否同意',
            'reason' => '拒绝退款原因'
        ];
    }
}
