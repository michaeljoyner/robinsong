<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class RegisterFormRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8'
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'That email has already been taken',
            'password.min' => 'Please use a password at least 8 characters long'
        ];
    }
}
