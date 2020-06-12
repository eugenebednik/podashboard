<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBuffRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_name' => 'required|min:2',
            'discord_snowflake' => 'required|size:18',
            'request_type_id' => 'required|integer|exists:request_types,id',
            'is_alt_request' => 'required|boolean',
            'alt_name' => 'required_if:is_alt_request,true',
            'outstanding' => 'required|integer|between:0,1',
        ];
    }
}
