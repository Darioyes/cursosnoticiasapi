<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class Update extends FormRequest
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
        //$id = Rule::unique('users')->ignore($this->user->id);
        return [
            'name' => 'required|min:3|max:100',
            'lastname' => 'required|min:3|max:100',
            'email' => 'required|email|unique:users',
        ];
    }
}
