<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function rules()
    {
        return [
            'role_id' => 'required|string|size:18',
            'role_name' => 'required',
        ];
    }
}
