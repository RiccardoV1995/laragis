<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => 'bail|required|email',
            'password' => 'required'
        ];
    }

    public function messages()
    {
        $messages = [
            'email.required' => 'Email is required',
            'email.email' => 'This is not a email',
            'password.required' => 'Password is required'
        ];

        return $messages;
    }
}
