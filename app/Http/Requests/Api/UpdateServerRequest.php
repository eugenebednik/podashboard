<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServerRequest extends FormRequest
{
    public function rules()
    {
        return [
            'webhook_url' => 'required|url|min:60'
        ];
    }
}
