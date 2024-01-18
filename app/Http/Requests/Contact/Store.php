<?php

namespace App\Http\Requests\Contact;

use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest
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
        return [
            'name' => 'required|string|min:3|max:50',
            'email' => 'required|email|min:3|max:50',
            'message' => 'required|string|min:3|max:500',
            'file'=> 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt|max:2048'
        ];
    }
}
