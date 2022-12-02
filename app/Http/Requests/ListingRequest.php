<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListingRequest extends FormRequest
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
            'company' => 'required',
            'title' => 'required',
            'location' => 'required',
            'email' => 'bail|required|email',
            'website' => 'required',
            'tags' => 'required',
            'description' => 'required'
        ];
    }

    public function messages()
    {
        $messages = [
            'company.required' => 'Company field is required',
            'title.required' => 'title field is required',
            'location.required' => 'location field is required',
            'email.required' => 'email field is required',
            'email.email' => 'is not a email',
            'website.required' => 'website field is required',
            'tags.required' => 'tags field is required',
            'description.required' => 'description field is required'
        ];
        return $messages;
    }
}
