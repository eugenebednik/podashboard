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
            'alt_request' => 'sometimes|boolean',
            'alt_name' => 'required_if:alt_request|string|min:2',
            'outstanding' => 'required|integer|between:0,1',
        ];
    }
}
