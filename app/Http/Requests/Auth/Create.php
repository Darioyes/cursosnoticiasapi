<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class Create extends FormRequest
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
        $passwordRule = Rules\Password::defaults()->mixedCase()->numbers()->min(8)->required();
        $numbers = Rules\Password::defaults()->numbers();
        return [
            'name' => 'required|min:3|max:100',
            'lastname' => 'required|min:3|max:100',
            'email' => 'required|email|unique:users',
            'password' => ['confirmed',$passwordRule,$numbers],//password_confirmation
            //'send_mail' => 'required|in:true,false',
            //terminos y condiciones siempre devolvera true
            'terms' => 'required|accepted',
        ];
    }
}
