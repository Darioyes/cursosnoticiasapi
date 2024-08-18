<?php

namespace App\Http\Requests\Articles;

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
            'subtitle' => 'required|string|min:5|max:255',
            'entrance' => 'required|string|min:5|max:1000',
            'body_news' => 'required|string|min:5|max:10000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'news_id' => 'required|exists:news,id',
            'article_image_id' => 'nullable|numeric|exists:article_images,id'
        ];
    }
}
