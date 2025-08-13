<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotificationsRequest extends FormRequest
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
            'title' => 'required|string|max:100',
            'content' => 'required|string|max:500',
            'sender_id' => 'required',
            'receiver_type' => 'required|in:user,shop,admin,all,employee',
            'direct_to' => 'nullable',
            'type' => 'required|in:promotion,system,security',
            'priority' => 'required|in:low,normal,high',
            'status' => 'nullable|in:pending,active,inactive,failed',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'status' => 'pending',
        ]);
    }

}
