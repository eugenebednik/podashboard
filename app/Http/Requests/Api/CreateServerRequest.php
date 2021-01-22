<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CreateServerRequest extends FormRequest
{
    public function rules() : array
    {
        return [
            'snowflake' => 'required|string|size:18',
            'name' => 'required',
            'webhook_id' => 'required|string|size:18',
            'webhook_token' => 'required',
        ];
    }
}
