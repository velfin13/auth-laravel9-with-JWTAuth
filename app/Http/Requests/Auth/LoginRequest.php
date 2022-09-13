<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            "email" => "required|email",
            "password" => "required|min:8"
        ];
    }
}
