<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CreateDoneRequest extends FormRequest
{
    public function rules() : array
    {
        return [
            'server_snowflake' => 'required|exists:servers,snowflake',
            'discord_snowflake' => 'required|string|size:18',
        ];
    }
}
