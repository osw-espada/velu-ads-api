<?php

namespace App\Laravel\Middlewares;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

class TransformInput extends Middleware
{
    /**
     * The names of the attributes that should not be trimmed.
     *
     * @var array<int, string>
     */
    protected $except = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Transform the given value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function transform($key, $value)
    {
        if (in_array($key, $this->except, true) || ! is_string($value)) {
            return $value;
        }

        $uppercase_inputs = ["firstname","lastname"];

        if(in_array($key,$uppercase_inputs)){
            return strtoupper($value);
        }

        return $value;
    }

}
