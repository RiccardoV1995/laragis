<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|min:8|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6'
        ];
    }

    public function messages()
    {
        $messages = [
            'name.required' => 'Name is required',
            'email.email' => 'This is not a email',
            'email.unique' => 'Email already exists',
            'email.required' => 'Email is required',
            'password.required_with' => 'Insert password confermation',
            'password.same' => 'Password not match',
            'password.min' => 'The password must be long then 8 letters',
            'password_confirmation.min' => 'The password must be long then 8 letters'
        ];

        return $messages;
    }
}
