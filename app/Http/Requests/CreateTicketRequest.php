<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTicketRequest extends FormRequest
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
            'subject' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'required|in:technical,billing,general,bug_report,feature_request,other',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx|max:5120', // 5MB max
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'subject.required' => 'Vui lòng nhập tiêu đề ticket',
            'subject.max' => 'Tiêu đề không được vượt quá 255 ký tự',
            'description.required' => 'Vui lòng nhập mô tả chi tiết',
            'description.min' => 'Mô tả phải có ít nhất 10 ký tự',
            'priority.required' => 'Vui lòng chọn mức độ ưu tiên',
            'priority.in' => 'Mức độ ưu tiên không hợp lệ',
            'category.required' => 'Vui lòng chọn danh mục',
            'category.in' => 'Danh mục không hợp lệ',
            'attachment.file' => 'File đính kèm không hợp lệ',
            'attachment.mimes' => 'File đính kèm phải là: jpg, jpeg, png, gif, pdf, doc, docx',
            'attachment.max' => 'File đính kèm không được vượt quá 5MB',
        ];
    }
}
