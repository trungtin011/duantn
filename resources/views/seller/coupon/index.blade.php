@extends('layouts.seller_home')

@section('content')
    <div class="mt-[32px] mb-[24px]">
        <h1 class="font-semibold text-[28px]">Mã giảm giá</h1>
        <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Trang chủ</a> / Mã giảm giá</div>
    </div>
    @include('layouts.notification')
    <div class="row g-3">
        {{-- Left Column: Add Coupon Form --}}
        <div class="col-md-4">
            <div class="p-[24px] bg-white rounded-[8px]">
                <h3 class="font-semibold text-lg mb-4">Thêm mã giảm giá</h3>
                <form action="{{ route('seller.coupon.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label for="code" class="form-label">Mã <span class="text-red-500">*</span></label>
                        <input type="text" class="form-control @error('code') border-red-500 @enderror" id="code" name="code" value="{{ old('code') }}" placeholder="Nhập mã giảm giá">
                        @error('code')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label for="name" class="form-label">Tên <span class="text-red-500">*</span></label>
                        <input type="text" class="form-control @error('name') border-red-500 @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Tên mã giảm giá">
                        @error('name')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control @error('description') border-red-500 @enderror" id="description" name="description" rows="2" placeholder="Mô tả ngắn">{{ old('description') }}</textarea>
                        @error('description')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label for="image" class="form-label">Ảnh mã giảm giá</label>
                        <input type="file" class="form-control-file @error('image') border-red-500 @enderror" id="image" name="image" accept="image/*">
                        @error('image')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="discount_value" class="form-label">Giá trị <span class="text-red-500">*</span></label>
                            <input type="number" class="form-control @error('discount_value') border-red-500 @enderror" id="discount_value" name="discount_value" value="{{ old('discount_value') }}" step="0.01" placeholder="Giá trị">
                            @error('discount_value')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label for="discount_type" class="form-label">Loại <span class="text-red-500">*</span></label>
                            <select class="form-select form-select-admin @error('discount_type') border-red-500 @enderror" id="discount_type" name="discount_type">
                                <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>%</option>
                                <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>VND</option>
                            </select>
                            @error('discount_type')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="quantity" class="form-label">Số lượng <span class="text-red-500">*</span></label>
                            <input type="number" class="form-control @error('quantity') border-red-500 @enderror" id="quantity" name="quantity" value="{{ old('quantity') }}" placeholder="Số lượng">
                            @error('quantity')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label for="rank_limit" class="form-label">Hạn chế hạng</label>
                            <select class="form-select form-select-admin @error('rank_limit') border-red-500 @enderror" id="rank_limit" name="rank_limit">
                                <option value="all" {{ old('rank_limit') == 'all' ? 'selected' : '' }}>Tất cả</option>
                                <option value="gold" {{ old('rank_limit') == 'gold' ? 'selected' : '' }}>Vàng</option>
                                <option value="silver" {{ old('rank_limit') == 'silver' ? 'selected' : '' }}>Bạc</option>
                                <option value="bronze" {{ old('rank_limit') == 'bronze' ? 'selected' : '' }}>Đồng</option>
                                <option value="diamond" {{ old('rank_limit') == 'diamond' ? 'selected' : '' }}>Kim cương</option>
                            </select>
                            @error('rank_limit')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-2">
                        <div>
                            <label class="form-label">Ngày bắt đầu <span class="text-red-500">*</span></label>
                            <div class="flex gap-1 w-full">
                                <select id="start_day" name="start_day" class="form-select form-select-admin @error('start_date') border-red-500 @enderror">
                                    <option value="">Ngày</option>
                                    @for($i = 1; $i <= 31; $i++)
                                        <option value="{{ $i }}" {{ old('start_day', date('j')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                <select id="start_month" name="start_month" class="form-select form-select-admin @error('start_date') border-red-500 @enderror">
                                    <option value="">Tháng</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ old('start_month', date('n')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                <select id="start_year" name="start_year" class="form-select form-select-admin @error('start_date') border-red-500 @enderror">
                                    <option value="">Năm</option>
                                    @for($i = date('Y'); $i <= date('Y') + 5; $i++)
                                        <option value="{{ $i }}" {{ old('start_year', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            @error('start_date')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="form-label">Ngày kết thúc <span class="text-red-500">*</span></label>
                            <div class="flex gap-1 w-full">
                                <select id="end_day" name="end_day" class="form-select form-select-admin @error('end_date') border-red-500 @enderror">
                                    <option value="">Ngày</option>
                                    @for($i = 1; $i <= 31; $i++)
                                        <option value="{{ $i }}" {{ old('end_day', date('j')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                <select id="end_month" name="end_month" class="form-select form-select-admin @error('end_date') border-red-500 @enderror">
                                    <option value="">Tháng</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ old('end_month', date('n')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                <select id="end_year" name="end_year" class="form-select form-select-admin @error('end_date') border-red-500 @enderror">
                                    <option value="">Năm</option>
                                    @for($i = date('Y'); $i <= date('Y') + 5; $i++)
                                        <option value="{{ $i }}" {{ old('end_year', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            @error('end_date')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="flex gap-4 items-center">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }} class="form-checkbox">
                            <span class="ml-2 text-sm">Kích hoạt</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }} class="form-checkbox">
                            <span class="ml-2 text-sm">Công khai</span>
                        </label>
                    </div>
                    <button type="submit" class="bg-[#28BCF9] hover:bg-[#3DA5F7] text-white w-full py-2 px-4 rounded-md flex items-center justify-center transition-all duration-300 font-semibold">
                        Thêm mã giảm giá
                    </button>
                </form>
            </div>
        </div>

        {{-- Right Column: Coupon List Table --}}
        <div class="col-md-8">
            <div class="p-[24px] bg-white rounded-[8px]">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
                    <form class="w-full md:w-[223px] relative" method="GET" action="{{ route('seller.coupon.index') }}">
                        <input name="search" class="w-full h-[42px] border border-[#F2F2F6] rounded-md py-2 pl-10 pr-4 text-xs placeholder:text-gray-400 focus:outline-none" placeholder="Tìm kiếm mã giảm giá" type="text" value="{{ request('search') }}" />
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">
                            <i class="fas fa-search text-[#55585b]"></i>
                        </span>
                    </form>
                    <div class="flex gap-4 items-center">
                        <button id="delete-selected" class="hidden bg-red-500 text-white px-4 py-2 rounded-md text-sm hover:bg-red-600">
                            Xóa đã chọn
                        </button>
                    </div>
                </div>
                <div class="table-responsive admin-table-container">
                    <table class="w-full text-xs text-left text-gray-400 border-t border-gray-100">
                        <thead class="text-gray-300 font-semibold border-b border-gray-100">
                            <tr>
                                <th class="w-6 py-3 pr-6">
                                    <input id="select-all" class="w-[18px] h-[18px]" type="checkbox" />
                                </th>
                                <th class="py-3">Ảnh</th>
                                <th class="py-3">Mã</th>
                                <th class="py-3">Tên</th>
                                <th class="py-3">Giảm giá</th>
                                <th class="py-3">Loại</th>
                                <th class="py-3">Trạng thái</th>
                                <th class="py-3 pr-6 text-right">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-900 font-normal">
                            @foreach ($coupons as $coupon)
                                <tr data-coupon-id="{{ $coupon->id }}">
                                    <td class="py-4 pr-6">
                                        <input class="select-item w-[18px] h-[18px]" type="checkbox" />
                                    </td>
                                    <td class="py-4">
                                        @if ($coupon->image)
                                            <img src="{{ Storage::url($coupon->image) }}" alt="{{ $coupon->name }}" class="w-10 h-10 rounded-md object-cover" width="40" height="40" />
                                        @else
                                            <span class="text-gray-400 text-xs">Không có ảnh</span>
                                        @endif
                                    </td>
                                    <td class="py-4 text-[13px]">{{ $coupon->code }}</td>
                                    <td class="py-4 text-[13px]">{{ $coupon->name }}</td>
                                    <td class="py-4 text-[13px]">
                                        {{ $coupon->discount_value }} {{ $coupon->discount_type === 'percentage' ? '%' : 'VND' }}
                                    </td>
                                    <td class="py-4 text-[13px]">{{ ucfirst($coupon->discount_type) }}</td>
                                    <td class="py-4">
                                        <span class="inline-block {{ $coupon->is_active ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">
                                            {{ $coupon->is_active ? 'Kích hoạt' : 'Không hoạt động' }}
                                        </span>
                                    </td>
                                    <td class="py-4 pr-6 text-right flex items-center gap-2 justify-end">
                                        <div class="bg-[#50cd89] hover:bg-[#16A34A] text-white w-[37px] h-[35px] rounded-md flex items-center justify-center">
                                            <a href="{{ route('seller.coupon.edit', $coupon->id) }}" class="transition-all duration-300">
                                                <i class="fas fa-pen" title="Sửa"></i>
                                            </a>
                                        </div>
                                        <form action="{{ route('seller.coupon.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa mã giảm giá này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="border hover:bg-[#F1416C] hover:text-white w-[37px] h-[35px] rounded-md flex items-center justify-center transition-all duration-300">
                                                <i title="Xóa">
                                                    <svg width="13" height="13" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M19.0697 4.23C17.4597 4.07 15.8497 3.95 14.2297 3.86V3.85L14.0097 2.55C13.8597 1.63 13.6397 0.25 11.2997 0.25H8.67967C6.34967 0.25 6.12967 1.57 5.96967 2.54L5.75967 3.82C4.82967 3.88 3.89967 3.94 2.96967 4.03L0.929669 4.23C0.509669 4.27 0.209669 4.64 0.249669 5.05C0.289669 5.46 0.649669 5.76 1.06967 5.72L3.10967 5.52C8.34967 5 13.6297 5.2 18.9297 5.73C18.9597 5.73 18.9797 5.73 19.0097 5.73C19.3897 5.73 19.7197 5.44 19.7597 5.05C19.7897 4.64 19.4897 4.27 19.0697 4.23Z" fill="currentColor"></path>
                                                        <path d="M17.2297 7.14C16.9897 6.89 16.6597 6.75 16.3197 6.75H3.67975C3.33975 6.75 2.99975 6.89 2.76975 7.14C2.53975 7.39 2.40975 7.73 2.42975 8.08L3.04975 18.34C3.15975 19.86 3.29975 21.76 6.78975 21.76H13.2097C16.6997 21.76 16.8398 19.87 16.9497 18.34L17.5697 8.09C17.5897 7.73 17.4597 7.39 17.2297 7.14ZM11.6597 16.75H8.32975C7.91975 16.75 7.57975 16.41 7.57975 16C7.57975 15.59 7.91975 15.25 8.32975 15.25H11.6597C12.0697 15.25 12.4097 15.59 12.4097 16C12.4097 16.41 12.0697 16.75 11.6597 16.75ZM12.4997 12.75H7.49975C7.08975 12.75 6.74975 12.41 6.74975 12C6.74975 11.59 7.08975 11.25 7.49975 11.25H12.4997C12.9097 11.25 13.2497 11.59 13.2497 12C13.2497 12.41 12.9097 12.75 12.4997 12.75Z" fill="currentColor"></path>
                                                    </svg>
                                                </i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            @if ($coupons->isEmpty())
                                <tr>
                                    <td colspan="8" class="text-center text-gray-400 py-4">Không có mã giảm giá nào</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="mt-6 flex items-center justify-between text-[11px] text-gray-500 select-none">
                    <div>
                        Hiển thị {{ $coupons->count() }} mã giảm giá trên {{ $coupons->total() }}
                    </div>
                    {{ $coupons->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('select-all');
            const itemCheckboxes = document.querySelectorAll('.select-item');
            const deleteSelectedButton = document.getElementById('delete-selected');

            // Hiện/ẩn nút Xóa
            function toggleDeleteButton() {
                const anyChecked = Array.from(itemCheckboxes).some(cb => cb.checked);
                deleteSelectedButton.classList.toggle('hidden', !anyChecked);
            }

            selectAllCheckbox.addEventListener('change', function() {
                itemCheckboxes.forEach(cb => cb.checked = this.checked);
                toggleDeleteButton();
            });

            itemCheckboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    selectAllCheckbox.checked = Array.from(itemCheckboxes).every(c => c.checked);
                    toggleDeleteButton();
                });
            });

            // Xóa hàng loạt bằng confirm
            deleteSelectedButton.addEventListener('click', function() {
                const selectedCheckboxes = document.querySelectorAll('.select-item:checked');
                if (selectedCheckboxes.length === 0) return;

                const ids = Array.from(selectedCheckboxes).map(cb => cb.closest('tr').dataset.couponId);

                if (confirm('Bạn có chắc chắn muốn xóa ' + ids.length + ' mã giảm giá đã chọn?')) {
                    // Tạo form ẩn để submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("seller.coupon.destroyMultiple") }}';
                    
                    // Thêm CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);
                    
                    // Thêm method DELETE
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    form.appendChild(methodField);
                    
                    // Thêm ids
                    const idsField = document.createElement('input');
                    idsField.type = 'hidden';
                    idsField.name = 'ids';
                    idsField.value = JSON.stringify(ids);
                    form.appendChild(idsField);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });

            // Xử lý logic ngày tháng năm
            function getDaysInMonth(month, year) {
                return new Date(year, month, 0).getDate();
            }

            function updateDays(daySelect, monthSelect, yearSelect) {
                const month = parseInt(monthSelect.value);
                const year = parseInt(yearSelect.value);
                
                if (month && year) {
                    const daysInMonth = getDaysInMonth(month, year);
                    const currentDay = parseInt(daySelect.value);
                    
                    // Lưu lại ngày hiện tại
                    const selectedDay = currentDay;
                    
                    // Xóa tất cả options cũ
                    daySelect.innerHTML = '<option value="">Ngày</option>';
                    
                    // Thêm options mới
                    for (let i = 1; i <= daysInMonth; i++) {
                        const option = document.createElement('option');
                        option.value = i;
                        option.textContent = i;
                        if (i === selectedDay) {
                            option.selected = true;
                        }
                        daySelect.appendChild(option);
                    }
                }
            }

            // Xử lý cho ngày bắt đầu
            const startMonth = document.getElementById('start_month');
            const startYear = document.getElementById('start_year');
            const startDay = document.getElementById('start_day');

            startMonth.addEventListener('change', function() {
                updateDays(startDay, startMonth, startYear);
            });

            startYear.addEventListener('change', function() {
                updateDays(startDay, startMonth, startYear);
            });

            // Xử lý cho ngày kết thúc
            const endMonth = document.getElementById('end_month');
            const endYear = document.getElementById('end_year');
            const endDay = document.getElementById('end_day');

            endMonth.addEventListener('change', function() {
                updateDays(endDay, endMonth, endYear);
            });

            endYear.addEventListener('change', function() {
                updateDays(endDay, endMonth, endYear);
            });

            // Thêm hidden inputs để gửi ngày hoàn chỉnh
            const form = document.querySelector('form');
            
            // Tạo hidden input cho start_date
            const startDateInput = document.createElement('input');
            startDateInput.type = 'hidden';
            startDateInput.name = 'start_date';
            startDateInput.id = 'start_date_hidden';
            form.appendChild(startDateInput);

            // Tạo hidden input cho end_date
            const endDateInput = document.createElement('input');
            endDateInput.type = 'hidden';
            endDateInput.name = 'end_date';
            endDateInput.id = 'end_date_hidden';
            form.appendChild(endDateInput);

            // Cập nhật hidden inputs khi form submit
            form.addEventListener('submit', function(e) {
                const startDay = document.getElementById('start_day').value;
                const startMonth = document.getElementById('start_month').value;
                const startYear = document.getElementById('start_year').value;
                
                const endDay = document.getElementById('end_day').value;
                const endMonth = document.getElementById('end_month').value;
                const endYear = document.getElementById('end_year').value;

                if (startDay && startMonth && startYear) {
                    const startDate = `${startYear}-${startMonth.padStart(2, '0')}-${startDay.padStart(2, '0')}`;
                    document.getElementById('start_date_hidden').value = startDate;
                }

                if (endDay && endMonth && endYear) {
                    const endDate = `${endYear}-${endMonth.padStart(2, '0')}-${endDay.padStart(2, '0')}`;
                    document.getElementById('end_date_hidden').value = endDate;
                }
            });
        });
    </script>
@endsection