<?php

namespace App\Http\Requests\Api\BuffRequests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBuffRequestRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_name' => 'required|min:2',
            'discord_snowflake' => 'required|size:18',
            'request_type_id' => 'required|integer|exists:request_types,id',
        ];
    }
}
