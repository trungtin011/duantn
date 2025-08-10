@php
    $author = $comment->user?->username ?? 'Ẩn danh';
@endphp
<div class="comment-item border-b border-gray-100 py-3">
    <div class="flex items-start gap-3">
        <div class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center text-sm font-semibold text-gray-600">
            {{ mb_strtoupper(mb_substr($author, 0, 1)) }}
        </div>
        <div class="flex-1">
            <div class="text-sm text-gray-800 font-semibold">{{ $author }}</div>
            <div class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</div>
            <div class="mt-1 text-[13px] leading-5 text-gray-800">{!! nl2br(e($comment->content)) !!}</div>
            @if($comment->children && $comment->children->count())
                <div class="mt-3 ml-8 space-y-3">
                    @foreach($comment->children as $child)
                        @include('user.post.partials.comment_item', ['comment' => $child])
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>


