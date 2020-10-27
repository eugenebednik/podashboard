<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CreateServerRequest extends FormRequest
{
    public function rules()
    {
        return [
            'snowflake' => 'required|string|size:18',
            'name' => 'required'
        ];
    }
}