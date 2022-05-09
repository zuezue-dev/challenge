<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'    => 'required|email|min:6',
            'password' => 'required|min:8'
        ];
    }

    public function messages()
    {
        return [
            'email.required'    => 'email is required',
            'email.email'       => 'email is invalid',
            'email.min'         => 'email is invalid',
            'password.required' => 'password is required',
            'password.min'      => 'password is invalid'
        ];
    }
}
