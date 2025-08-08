@if($banners->hasPages())
<div class="flex items-center justify-between">
    <div class="flex-1 flex justify-between sm:hidden">
        @if ($banners->onFirstPage())
            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-md">
                Trước
            </span>
        @else
            <a href="{{ $banners->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:text-gray-500">
                Trước
            </a>
        @endif

        @if ($banners->hasMorePages())
            <a href="{{ $banners->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:text-gray-500">
                Sau
            </a>
        @else
            <span class="ml-3 relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-md">
                Sau
            </span>
        @endif
    </div>
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-gray-700">
                Hiển thị
                <span class="font-medium">{{ $banners->firstItem() }}</span>
                đến
                <span class="font-medium">{{ $banners->lastItem() }}</span>
                trong tổng số
                <span class="font-medium">{{ $banners->total() }}</span>
                kết quả
            </p>
        </div>
        <div>
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                {{-- Previous Page Link --}}
                @if ($banners->onFirstPage())
                    <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 cursor-default">
                        <span class="sr-only">Trước</span>
                        <i class="fas fa-chevron-left"></i>
                    </span>
                @else
                    <a href="{{ $banners->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Trước</span>
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($banners->getUrlRange(1, $banners->lastPage()) as $page => $url)
                    @if ($page == $banners->currentPage())
                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-blue-50 text-sm font-medium text-blue-600">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($banners->hasMorePages())
                    <a href="{{ $banners->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Sau</span>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 cursor-default">
                        <span class="sr-only">Sau</span>
                        <i class="fas fa-chevron-right"></i>
                    </span>
                @endif
            </nav>
        </div>
    </div>
</div>
@endif
