@props(['user' => null, 'size' => 'md', 'showName' => false, 'className' => ''])

@php
    $user = $user ?? auth()->user();
    $sizeClasses = [
        'xs' => 'w-4 h-4',
        'sm' => 'w-6 h-6', 
        'md' => 'w-8 h-8',
        'lg' => 'w-12 h-12',
        'xl' => 'w-16 h-16',
        '2xl' => 'w-20 h-20'
    ];
    $textSizes = [
        'xs' => 'text-xs',
        'sm' => 'text-xs',
        'md' => 'text-sm',
        'lg' => 'text-base',
        'xl' => 'text-lg',
        '2xl' => 'text-xl'
    ];
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
    $textSize = $textSizes[$size] ?? $textSizes['md'];
@endphp

<div class="flex items-center gap-2 {{ $className }}">
    <div class="{{ $sizeClass }} rounded-full flex items-center justify-center overflow-hidden avatar-container">
        @if($user && $user->avatar)
            <img src="{{ getUserAvatar($user->avatar) }}" 
                 alt="{{ $user->fullname ?? $user->username ?? 'User' }}" 
                 class="w-full h-full rounded-full object-cover user-avatar">
        @else
            <div class="w-full h-full rounded-full avatar-placeholder {{ $textSize }}">
                {{ strtoupper(substr($user->fullname ?? $user->username ?? 'U', 0, 1)) }}
            </div>
        @endif
    </div>
    
    @if($showName && $user)
        <span class="text-sm font-medium text-gray-900">
            {{ $user->fullname ?? $user->username }}
        </span>
    @endif
</div> 