<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CreateAllianceRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|size:3|unique:alliances,name'
        ];
    }
}
