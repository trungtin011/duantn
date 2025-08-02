<aside class="flex flex-col w-16 bg-blue-700 text-white items-center py-4 space-y-6">
    @auth
        <img alt="Avatar" class="rounded-full w-10 h-10" height="40" src="{{ Auth::user()->avatar ? \Illuminate\Support\Facades\Storage::url(Auth::user()->avatar) : asset('images/default_avatar.png') }}" width="40"/>
    @endauth
    <button aria-label="Messages" class="relative w-10 h-10 flex items-center justify-center hover:bg-blue-800 rounded">
        <i class="fas fa-comment-alt text-xl"></i>
        {{-- <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-semibold rounded-full w-5 h-5 flex items-center justify-center">5</span> --}}
    </button>
    {{-- <button aria-label="Documents" class="w-10 h-10 flex items-center justify-center hover:bg-blue-800 rounded">
        <i class="fas fa-file-alt text-xl"></i>
    </button> --}}
</aside> 