<!-- Modal -->
<div id="addressModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 hidden">
    <div class="bg-white w-[600px] rounded-lg p-6 shadow-lg relative">
        <!-- Close button -->
        <button onclick="closeModal()"
            class="absolute top-2 right-3 text-gray-500 hover:text-black text-xl">&times;</button>

        <h2 class="text-xl font-semibold mb-4">Thêm Địa Chỉ Mới</h2>

        <div class="space-y-4">
            <div>
                <label class="block text-sm mb-1">Họ & Tên</label>
                <input type="text" class="w-full border p-2 rounded" placeholder="Nhập vào">
            </div>
            <div>
                <label class="block text-sm mb-1">Số điện thoại</label>
                <input type="text" class="w-full border p-2 rounded" placeholder="Nhập vào">
            </div>
            <div>
                <label class="block text-sm mb-1">Địa chỉ</label>
                <select class="w-full border p-2 rounded">
                    <option>Chọn</option>
                    <!-- Các tỉnh/thành -->
                </select>
            </div>
            <div>
                <label class="block text-sm mb-1">Địa chỉ chi tiết</label>
                <textarea class="w-full border p-2 rounded" rows="3" placeholder="Số nhà, tên đường..."></textarea>
            </div>
            {{-- <div class="border rounded p-2 flex items-center gap-2 cursor-pointer hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                </svg>

                <span class="text-sm">Định vị<br><span class="text-xs text-gray-400">Giúp đơn hàng được giao nhanh
                        nhất</span></span>
            </div> --}}
        </div>

        <!-- Buttons -->
        <div class="flex justify-end gap-3 mt-6">
            <button onclick="closeModal()" class="px-4 py-2 bg-white border rounded hover:bg-gray-100">Hủy</button>
            <button class="px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700">Lưu</button>
        </div>
    </div>
</div>
