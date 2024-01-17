<?php

namespace App\Http\Requests\News;

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
            'epigraph' => 'required|string|nullable|min:10|max:500',
            'title' => 'required|string|min:10|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'content' => 'required|in:news,course',
            'featured' => 'required|numeric|in:1,0',
            'visible' => 'required|numeric|in:1,0',
            'category_news_id' => 'nullable|numeric|exists:categories_news,id',
            'category_course_id' => 'nullable|numeric|exists:categories_courses,id',
        ];
    }
}
