<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'nullable|integer|exists:comments,id',
            'chapter_id' => 'required|integer|exists:chapters,id',
            'user_id' => 'required|integer|exists:users,id',
            'content' => 'required|string',
            'parent_id' => 'nullable|integer|exists:comments,id',
        ];
    }

    public function messages(): array
    {
        return [
            'id.integer' => 'ID phải là số nguyên.',
            'id.exists' => 'ID bình luận không hợp lệ.',

            'chapter_id.required' => 'Chapter không được để trống.',
            'chapter_id.integer' => 'Chapter phải là số nguyên.',
            'chapter_id.exists' => 'Chapter không tồn tại.',

            'user_id.required' => 'User không được để trống.',
            'user_id.integer' => 'User phải là số nguyên.',
            'user_id.exists' => 'User không tồn tại.',

            'content.required' => 'Nội dung không được để trống.',
            'content.string' => 'Nội dung phải là chuỗi ký tự.',

            'parent_id.integer' => 'Parent ID phải là số nguyên.',
            'parent_id.exists' => 'Parent comment không hợp lệ.',
        ];
    }
}
