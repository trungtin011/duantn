<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReplyTicketRequest extends FormRequest
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
            'message' => 'required|string|min:5',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx|max:5120', // 5MB max
            'is_internal' => 'boolean', // Chỉ admin mới có thể set true
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'message.required' => 'Vui lòng nhập nội dung phản hồi',
            'message.min' => 'Nội dung phản hồi phải có ít nhất 5 ký tự',
            'attachment.file' => 'File đính kèm không hợp lệ',
            'attachment.mimes' => 'File đính kèm phải là: jpg, jpeg, png, gif, pdf, doc, docx',
            'attachment.max' => 'File đính kèm không được vượt quá 5MB',
        ];
    }
}
