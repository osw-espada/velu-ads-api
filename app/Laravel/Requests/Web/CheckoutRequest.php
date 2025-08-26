<?php namespace App\Laravel\Requests\Web;

use App\Laravel\Requests\RequestManager;

class CheckoutRequest extends RequestManager{
    public function rules(){
        $rules = [
            'description' => "required|between:2,150|regex:/^[A-Za-z0-9'-. \s]+$/",
            'amount' => "required|min:0.01",
            'reference_number' => "required|unique_reference",
        ];


        return $rules;
    }

    public function messages(){
        return [
            'required'	=> "Field is required.",
            'description.regex' => "Invalid description.",
            'amount.min' => "Amount must be greater than 0.01.",
            'name.between' => "Description should be between 2 to 150 characters only.",
            'reference_number.unique_reference' => "Reference number already used. Please try another.",
        ];
    }
}
