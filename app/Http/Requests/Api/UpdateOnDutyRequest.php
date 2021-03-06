<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOnDutyRequest extends FormRequest
{
    public function rules() : array
    {
        return [
            'user_id' => 'required|exists:users,id',
        ];
    }
}
