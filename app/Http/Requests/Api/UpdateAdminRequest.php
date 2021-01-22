<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminRequest extends FormRequest
{
    public function rules() : array
    {
        return [
            'user_id' => 'required|int',
        ];
    }
}
