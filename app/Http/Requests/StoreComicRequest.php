<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreComicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'nullable|integer|exists:comics,id',

            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('comics', 'title')->ignore($this->id),
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('comics', 'slug')->ignore($this->id),
            ],

            'description' => 'nullable|string',
            'author' => 'nullable|string|max:255',
            'artist' => 'nullable|string|max:255',
            'status' => 'required|string|in:ongoing,completed',
            'hidden' => 'required|integer|in:0,1',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'url_image' => 'nullable|string|max:255',
            'views' => 'nullable|integer',
            'votes' => 'nullable|integer',
            'ratings' => 'nullable|numeric|min:0|max:5',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:255',
            'crawl' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'id.integer' => 'ID phải là số nguyên.',
            'id.exists' => 'ID không hợp lệ.',

            'title.required' => 'Tên truyện không được để trống.',
            'title.string' => 'Tên truyện phải là chuỗi ký tự.',
            'title.max' => 'Tên truyện không được vượt quá 255 ký tự.',
            'title.unique' => 'Tên truyện đã tồn tại.',

            'slug.string' => 'Slug phải là chuỗi.',
            'slug.max' => 'Slug không được vượt quá 255 ký tự.',
            'slug.unique' => 'Slug đã tồn tại.',

            'description.string' => 'Mô tả phải là chuỗi.',
            'author.string' => 'Tác giả phải là chuỗi.',
            'author.max' => 'Tác giả không được vượt quá 255 ký tự.',
            'artist.string' => 'Họa sĩ phải là chuỗi.',
            'artist.max' => 'Họa sĩ không được vượt quá 255 ký tự.',
            'status.required' => 'Trạng thái không được để trống.',
            'status.string' => 'Trạng thái phải là chuỗi.',
            'status.in' => 'Trạng thái chỉ có thể là "ongoing" hoặc "completed".',

            'hidden.required' => 'Trạng thái không được để trống.',
            'hidden.integer' => 'Trạng thái phải là chuỗi.',
            'hidden.in' => 'Trạng thái chỉ có thể là "hiển thị" hoặc "ẩn".',

            'image.image' => 'Ảnh bìa phải là định dạng hình ảnh.',
            'image.mimes' => 'Ảnh bìa chỉ chấp nhận định dạng jpg, jpeg, png, webp.',
            'image.max' => 'Ảnh bìa không được vượt quá 2MB.',

            'url_image.string' => 'URL ảnh phải là chuỗi.',
            'url_image.max' => 'URL ảnh không được vượt quá 255 ký tự.',

            'views.integer' => 'Lượt xem phải là số nguyên.',
            'votes.integer' => 'Lượt vote phải là số nguyên.',
            'ratings.numeric' => 'Đánh giá phải là số.',
            'ratings.min' => 'Đánh giá không được nhỏ hơn 0.',
            'ratings.max' => 'Đánh giá không được lớn hơn 5.',

            'meta_title.string' => 'Meta title phải là chuỗi.',
            'meta_title.max' => 'Meta title không được vượt quá 255 ký tự.',
            'meta_description.string' => 'Meta description phải là chuỗi.',
            'meta_keywords.string' => 'Meta keywords phải là chuỗi.',
            'meta_keywords.max' => 'Meta keywords không được vượt quá 255 ký tự.',

            'url.string' => 'URL phải là chuỗi.',
            'url.max' => 'URL không được vượt quá 255 ký tự.',

            'crawl.boolean' => 'Crawl phải là true hoặc false.',
        ];
    }
}