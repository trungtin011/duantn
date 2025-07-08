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
            'selected_address_id' => 'required|exists:user_addresses,id',
            'payment_method' => 'required|in:MOMO,VNPAY,COD,PAYPAL',
            'shop_notes' => 'nullable|string',
            'shipping_fee' => 'required|numeric',
            'subtotal' => 'required|numeric',
            'discount_amount' => 'required|numeric',
            'total_amount' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'selected_address_id.required' => 'Địa chỉ người nhận không được để trống',
            'selected_address_id.exists' => 'Địa chỉ người nhận không tồn tại',
            'payment_method.required' => 'Phương thức thanh toán không được để trống',
            'payment_method.in' => 'Phương thức thanh toán không hợp lệ',
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
