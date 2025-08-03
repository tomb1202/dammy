<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreChapterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'nullable|integer|exists:chapters,id',

            'comic_id' => 'required|integer|exists:comics,id',
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'number' => 'nullable|integer',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('chapters', 'slug')
                    ->where('comic_id', $this->comic_id)
                    ->ignore($this->id),
            ],

            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:255',
            'crawl' => 'nullable|boolean',

            // Pages
            'pages.*.id' => 'nullable|integer|exists:chapter_pages,id',
            'pages.*.sort' => 'nullable|integer|min:0',
            'pages.*.image' => 'nullable|image|mimes:jpeg,jpg,png,webp,gif|max:2048',

            'new_pages.*.id' => 'nullable|integer',
            'new_pages.*.sort' => 'nullable|integer|min:0',
            'new_pages.*.image' => 'nullable|image|mimes:jpeg,jpg,png,webp,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'comic_id.required' => 'Vui lòng chọn truyện.',
            'comic_id.integer' => 'ID truyện phải là số nguyên.',
            'comic_id.exists' => 'Truyện không tồn tại.',

            'title.required' => 'Tiêu đề không được để trống.',
            'title.string' => 'Tiêu đề phải là chuỗi ký tự.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',

            'number.integer' => 'Số chương phải là số nguyên.',

            'slug.string' => 'Slug phải là chuỗi.',
            'slug.max' => 'Slug không được vượt quá 255 ký tự.',
            'slug.unique' => 'Slug đã tồn tại.',

            'meta_title.max' => 'Meta title không được vượt quá 255 ký tự.',
            'meta_keywords.max' => 'Meta keywords không được vượt quá 255 ký tự.',

            'pages.*.image.image' => 'File phải là hình ảnh.',
            'pages.*.image.mimes' => 'Ảnh phải có định dạng jpeg, jpg, png, webp hoặc gif.',
            'pages.*.image.max' => 'Kích thước ảnh không được vượt quá 2MB.',
            'pages.*.sort.integer' => 'Thứ tự hiển thị phải là số nguyên.',

            'new_pages.*.image' => 'File phải là hình ảnh.',
            'new_pages.*.mimes' => 'Ảnh phải có định dạng jpeg, jpg, png, webp hoặc gif.',
            'new_pages.*.max' => 'Kích thước ảnh không được vượt quá 2MB.',
        ];
    }
}
