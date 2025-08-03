<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'nullable|integer|exists:categories,id',

            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($this->id),
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('categories', 'slug')->ignore($this->id),
            ],

            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:255',

            'sort' => 'nullable|integer',
            'url' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'id.integer' => 'ID phải là số nguyên.',
            'id.exists' => 'ID không hợp lệ.',

            'name.required' => 'Tên thể loại không được để trống.',
            'name.string' => 'Tên thể loại phải là chuỗi ký tự.',
            'name.max' => 'Tên thể loại không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên thể loại đã tồn tại.',

            'slug.string' => 'Slug phải là chuỗi.',
            'slug.max' => 'Slug không được vượt quá 255 ký tự.',
            'slug.unique' => 'Slug đã tồn tại.',

            'meta_title.string' => 'Meta title phải là chuỗi.',
            'meta_title.max' => 'Meta title không được vượt quá 255 ký tự.',

            'meta_description.string' => 'Meta description phải là chuỗi.',

            'meta_keywords.string' => 'Meta keywords phải là chuỗi.',
            'meta_keywords.max' => 'Meta keywords không được vượt quá 255 ký tự.',

            'sort.integer' => 'Sort phải là số nguyên.',
            'url.string' => 'URL phải là chuỗi.',
            'url.max' => 'URL không được vượt quá 255 ký tự.',
        ];
    }
}
