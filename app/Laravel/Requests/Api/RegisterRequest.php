<?php

namespace App\Laravel\Requests\Api;

use App\Laravel\Requests\ApiRequestManager;

class RegisterRequest extends ApiRequestManager
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(){

        return [
            'email' => "required|email:rfc,strict,dns,filter|unique_email:0",
            'name' => "required|between:2,100|regex:/^[A-Za-z0-9 \s]+$/",
            'password' => [
                'required',
                'between:6,20',
                'password_format',
                'confirmed'
            ],
        ];

    }

    public function messages(){
        return [
            'required' => "Field is required.",
            'regex' => "Invalid data.",
            'name.between' => "Firstname should be between 2 to 100 characters only.",
            'contact_number.phone' => "Invalid contact number format.",
            'password.between' => "Password should be between 6 to 20 characters",
            'password_format' => "Password must be 6-20 characters long, contain at least one uppercase letter, and at least one special character.",
            'password.confirmed' => "Password mismatch",
            'unique_phone' => "Phone Number already used.",
            'unique_email' => "Email address already used.",

        ];
    }
}
