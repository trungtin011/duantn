<aside class="flex flex-col w-16 bg-gradient-to-b from-[#ef3248] to-[#d91f35] text-white items-center py-6 space-y-8 shadow-lg">
    @auth
        <div class="relative">
            <img alt="Avatar" class="rounded-full w-12 h-12 border-2 border-white shadow-lg" height="48" src="{{ Auth::user()->avatar ? \Illuminate\Support\Facades\Storage::url(Auth::user()->avatar) : asset('images/default_avatar.png') }}" width="48"/>
            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
        </div>
    @endauth
    
    <div class="flex flex-col items-center space-y-6">
        <button aria-label="Messages" class="relative w-12 h-12 flex items-center justify-center hover:bg-[#d91f35] rounded-xl transition-all duration-200 group">
            <i class="fas fa-comment-alt text-xl group-hover:scale-110 transition-transform duration-200"></i>
            <div class="absolute -top-1 -right-1 w-5 h-5 bg-white text-[#ef3248] text-xs font-bold rounded-full flex items-center justify-center animate-pulse">3</div>
        </button>
        
        <button aria-label="Settings" class="w-12 h-12 flex items-center justify-center hover:bg-[#d91f35] rounded-xl transition-all duration-200 group">
            <i class="fas fa-cog text-xl group-hover:rotate-90 transition-transform duration-300"></i>
        </button>
        
        <button aria-label="Help" class="w-12 h-12 flex items-center justify-center hover:bg-[#d91f35] rounded-xl transition-all duration-200 group">
            <i class="fas fa-question-circle text-xl group-hover:scale-110 transition-transform duration-200"></i>
        </button>
    </div>
    
    <div class="mt-auto">
        <button aria-label="Logout" class="w-12 h-12 flex items-center justify-center hover:bg-red-500 rounded-xl transition-all duration-200 group">
            <i class="fas fa-sign-out-alt text-xl group-hover:scale-110 transition-transform duration-200"></i>
        </button>
    </div>
</aside> 