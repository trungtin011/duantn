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
            'title' => 'required',
            'content' => 'required',
            'sender_id' => 'required|exists:users,id',
            'receiver_type' => 'required|in:user,shop,admin,all,employee',
            'direct_to' => 'nullable|exists:users,id|exists:shops,id',
            'type' => 'required|in:promotion,system',
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
