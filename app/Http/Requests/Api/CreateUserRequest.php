<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|min:2',
            'alliance_id' => 'required|exists:alliances,id',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'is_admin' => 'required|integer|between:0,1',
            'active' => 'required|integer|between:0,1',
        ];
    }
}
