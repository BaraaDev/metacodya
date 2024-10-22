<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = auth()->id();
        return [
            'name'     => 'required|string|max:255|min:3',
            'email'    => 'required|string|email:rfc,dns|max:255|unique:users,email,'. $userId,
            'phone'    => 'required|string|max:15|unique:users,phone, '. $userId,
            'password' => 'required|string|min:6|confirmed',
        ];
    }
}
