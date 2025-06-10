<div class="grid grid-cols-1 gap-4">
    <input name="receiver_name" value="{{ old('receiver_name', $address->receiver_name ?? '') }}" placeholder="Người nhận" required class="border rounded px-3 py-2" />
    <input name="receiver_phone" value="{{ old('receiver_phone', $address->receiver_phone ?? '') }}" placeholder="SĐT" required class="border rounded px-3 py-2" />
    <input name="address" value="{{ old('address', $address->address ?? '') }}" placeholder="Địa chỉ chi tiết" required class="border rounded px-3 py-2" />
    <input name="ward" value="{{ old('ward', $address->ward ?? '') }}" placeholder="Phường/Xã" required class="border rounded px-3 py-2" />
    <input name="district" value="{{ old('district', $address->district ?? '') }}" placeholder="Quận/Huyện" required class="border rounded px-3 py-2" />
    <input name="province" value="{{ old('province', $address->province ?? '') }}" placeholder="Tỉnh/Thành" required class="border rounded px-3 py-2" />
    <input name="zip_code" value="{{ old('zip_code', $address->zip_code ?? '') }}" placeholder="Mã bưu điện" required class="border rounded px-3 py-2" />

    <select name="address_type" class="border rounded px-3 py-2">
        <option value="home" {{ old('address_type', $address->address_type ?? '') === 'home' ? 'selected' : '' }}>Nhà riêng</option>
        <option value="office" {{ old('address_type', $address->address_type ?? '') === 'office' ? 'selected' : '' }}>Công ty</option>
        <option value="other" {{ old('addr  ess_type', $address->address_type ?? '') === 'other' ? 'selected' : '' }}>Khác</option>
    </select>



    <label><input type="checkbox" name="is_default" {{ old('is_default', $address->is_default ?? false) ? 'checked' : '' }}> Đặt làm mặc định</label>

    <textarea name="note" class="border rounded px-3 py-2" placeholder="Ghi chú">{{ old('note', $address->note ?? '') }}</textarea>

    <button type="submit" class="bg-black text-white px-4 py-2 rounded">Lưu</button>
</div>
