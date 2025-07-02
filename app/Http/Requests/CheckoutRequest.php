<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'address' => 'required|exists:user_addresses,id',
            'payment' => 'required|in:MOMO,VNPAY,COD,PAYPAL',    
        ];

    }

    public function messages(): array
    {
        return [
            'address.required' => 'Địa chỉ người nhận không được để trống',
            'address.exists' => 'Địa chỉ người nhận không tồn tại',
            'payment.required' => 'Phương thức thanh toán không được để trống',
            'payment.in' => 'Phương thức thanh toán không hợp lệ',
        ];
    }

    public function attributes()
    {
        return [
            'address' => 'Địa chỉ',
            'payment' => 'Phương thức thanh toán',
        ];
    }
}
