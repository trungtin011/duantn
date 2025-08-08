@forelse ($banners as $banner)
<tr class="hover:bg-gray-50">
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
            <div class="flex-shrink-0 h-16 w-24">
                <img class="h-16 w-24 object-cover rounded-lg" 
                     src="{{ $banner->image_url }}" 
                     alt="{{ $banner->title }}">
            </div>
        </div>
    </td>
    <td class="px-6 py-4">
        <div class="text-sm">
            <div class="font-medium text-gray-900">{{ $banner->title }}</div>
            @if($banner->description)
                <div class="text-gray-500 text-xs mt-1">{{ Str::limit($banner->description, 50) }}</div>
            @endif
            @if($banner->link_url)
                <div class="text-blue-600 text-xs mt-1">
                    <a href="{{ $banner->link_url }}" target="_blank" class="hover:underline">
                        {{ Str::limit($banner->link_url, 30) }}
                    </a>
                </div>
            @endif
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
            {{ $banner->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
            {{ $banner->status === 'active' ? 'Hoạt động' : 'Không hoạt động' }}
        </span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
        {{ $banner->sort_order }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
        {{ $banner->created_at->format('d/m/Y H:i') }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
        <div class="flex items-center justify-end gap-2">
            <!-- Toggle Status -->
            <button onclick="toggleStatus({{ $banner->id }})" 
                    class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-50"
                    title="{{ $banner->status === 'active' ? 'Tắt hoạt động' : 'Bật hoạt động' }}">
                <i class="fas {{ $banner->status === 'active' ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
            </button>
            
            <!-- Edit -->
            <a href="{{ route('admin.banners.edit', $banner->id) }}" 
               class="text-yellow-600 hover:text-yellow-900 p-1 rounded hover:bg-yellow-50"
               title="Chỉnh sửa">
                <i class="fas fa-edit"></i>
            </a>
            
            <!-- View -->
            <a href="{{ route('admin.banners.show', $banner->id) }}" 
               class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50"
               title="Xem chi tiết">
                <i class="fas fa-eye"></i>
            </a>
            
            <!-- Delete -->
            <button onclick="deleteBanner({{ $banner->id }})" 
                    class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50"
                    title="Xóa">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
        <div class="flex flex-col items-center py-8">
            <i class="fas fa-image text-4xl text-gray-300 mb-4"></i>
            <p class="text-lg font-medium text-gray-400">Không có banner nào</p>
            <p class="text-sm text-gray-300 mt-1">Hãy thêm banner đầu tiên để bắt đầu</p>
        </div>
    </td>
</tr>
@endforelse
