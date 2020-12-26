<?php


namespace App\Http\Requests\Api;


use Illuminate\Foundation\Http\FormRequest;

class IndexBuffRequests extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }
}
