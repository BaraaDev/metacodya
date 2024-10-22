<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return string[]
     */
    public function rules() : array
    {

        return [
            'name'     => 'required|string|max:255|min:3',
            'email'    => 'required|string|email:rfc,dns|max:255|unique:users',
            'phone'    => 'required|string|max:15|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ];
    }
}
