@extends('layouts.app')
@push('styles')
    @vite('resources/css/user/post.css')
@endpush
@section('title', 'Bài viết')
@section('content')
    <div class="container_blog_detail">

        <main class="main-content_detail">
            <div class="mainProductImage flex items-center justify-center">
                <img style="height: 450px" src="{{ asset($post->photo) }}" alt="{{ $post->title }}">
            </div>

            <div class="post-header">
                <h1>{{ $post->title }}</h1>
                <div class="post-meta">
                    <span>Bởi {{ $post->author_info->username ?? 'Anonymous' }}</span>
                    <span>{{ $post->created_at->format('d M, Y. D') }}</span>
                    <span>Bình luận ({{ $post->comments->count() }})</span>
                </div>
            </div>

            <div class="reactions">
                <div class="reaction">
                    <button type="button" class="reaction-emoji react-btn" title="Thích" data-type="smile" data-id="{{ $post->id }}">👍</button>
                    <div class="reaction-count" id="reaction-smile-{{ $post->id }}">{{ method_exists($post, 'likes') ? $post->likes()->where('type','smile')->count() : 0 }}</div>
                </div>
                <div class="reaction">
                    <button type="button" class="reaction-emoji react-btn" title="Yêu thích" data-type="love" data-id="{{ $post->id }}">❤️</button>
                    <div class="reaction-count" id="reaction-love-{{ $post->id }}">{{ method_exists($post, 'likes') ? $post->likes()->where('type','love')->count() : 0 }}</div>
                </div>
                <div class="reaction">
                    <button type="button" class="reaction-emoji react-btn" title="Vui" data-type="grin" data-id="{{ $post->id }}">😊</button>
                    <div class="reaction-count" id="reaction-grin-{{ $post->id }}">{{ method_exists($post, 'likes') ? $post->likes()->where('type','grin')->count() : 0 }}</div>
                </div>
                <div class="reaction">
                    <button type="button" class="reaction-emoji react-btn" title="Ngạc nhiên" data-type="wow" data-id="{{ $post->id }}">😮</button>
                    <div class="reaction-count" id="reaction-wow-{{ $post->id }}">{{ method_exists($post, 'likes') ? $post->likes()->where('type','wow')->count() : 0 }}</div>
                </div>
                <div class="reaction">
                    <button type="button" class="reaction-emoji react-btn" title="Buồn cười" data-type="joy" data-id="{{ $post->id }}">😂</button>
                    <div class="reaction-count" id="reaction-joy-{{ $post->id }}">{{ method_exists($post, 'likes') ? $post->likes()->where('type','joy')->count() : 0 }}</div>
                </div>
                <div class="reaction">
                    <button type="button" class="reaction-emoji react-btn" title="Tức giận" data-type="angry" data-id="{{ $post->id }}">😡</button>
                    <div class="reaction-count" id="reaction-angry-{{ $post->id }}">{{ method_exists($post, 'likes') ? $post->likes()->where('type','angry')->count() : 0 }}</div>
                </div>
            </div>

            <div class="content-section">
                @if ($post->quote)
                    <blockquote> <i class="fa fa-quote-left"></i> {!! $post->quote !!}</blockquote>
                @endif
                <p>{!! $post->description !!}</p>
            </div>

            <div class="tags">
                <h4>Thẻ:</h4>
                <ul class="tag-inner flex items-center gap-2">
                    @php
                        $postTagNames = array_filter(array_map('trim', explode(',', (string) $post->tags)));
                    @endphp
                    @foreach ($postTagNames as $name)
                        <li><a href="{{ route('blog.tag', $name) }}">{{ $name }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="comment-section">
                @if(isset($userComment) && $userComment)
                    <div class="p-3 border rounded bg-gray-50 mb-3 text-sm">
                        Bạn đã bình luận: “{{ Str::limit($userComment->content, 120) }}”
                        <span class="text-gray-500">({{ $userComment->created_at->diffForHumans() }})</span>
                    </div>
                @endif
                <form class="comment-form" id="commentForm" method="POST" action="{{ route('blog.comment', $post->id) }}">
                    @csrf
                    <textarea name="content" id="commentContent" placeholder="Bình luận của bạn *" required minlength="5" maxlength="2000" {{ isset($userComment) && $userComment ? '' : '' }}></textarea>
                    <button type="submit" class="btn">{{ isset($userComment) && $userComment ? 'CẬP NHẬT BÌNH LUẬN' : 'ĐĂNG BÌNH LUẬN' }}</button>
                </form>
                <h4 style="margin-top: 20px;">Bình luận (<span id="commentCount">{{ $post->allComments()->count() }}</span>)</h4>
                <div id="commentsList" class="mt-3 divide-y divide-gray-100">
                    @foreach($post->comments as $comment)
                        @include('user.post.partials.comment_item', ['comment' => $comment])
                    @endforeach
                    @if($post->comments->isEmpty())
                        <div class="text-sm text-gray-500 py-3">Chưa có bình luận nào. Hãy là người đầu tiên bình luận!</div>
                    @endif
                </div>
            </div>
        </main>

        <aside class="sidebar">
            <div class="sidebar-section">
                <form action="{{ route('blog.search') }}" method="GET" style="display:flex; gap:10px; width:100%" onsubmit="return this.search.value.trim().length > 0;">
                    <input type="text" class="search-box" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm bài viết..." aria-label="Tìm kiếm bài viết" style="flex:1">
                    <button class="search-btn" type="submit" aria-label="Tìm kiếm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                </form>
            </div>

            <div class="sidebar-section">
                <h3 class="sidebar-title">Danh mục bài viết</h3>
                <ul class="category-list">
                    @php
                        // Lấy danh mục từ bảng post_categories
                        $sidebarCategories = \App\Models\PostCategory::where('status', 'active')->orderBy('title', 'ASC')->get();
                    @endphp
                    @if($sidebarCategories->count())
                        @foreach($sidebarCategories as $category)
                            <li>
                                <a href="{{ route('blog.category', $category->slug) }}">
                                    {{ $category->title }}
                                </a>
                            </li>
                        @endforeach
                    @else
                        <li><em>Không tìm thấy danh mục.</em></li>
                    @endif
                </ul>
            </div>

            <div class="sidebar-section">
                <h3 class="sidebar-title">Bài đăng gần đây</h3>
                <ul class="recent-posts">
                    @php
                        // Lấy 3 bài đăng gần đây nhất
                        $sidebarRecentPosts = \App\Models\Post::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
                    @endphp
                    @if($sidebarRecentPosts->count())
                        @foreach($sidebarRecentPosts as $recent)
                            <li>
                                <div class="recent-post-item">
                                    <div class="recent-post-thumb">
                                        @if($recent->photo && filter_var($recent->photo, FILTER_VALIDATE_URL))
                                            <img src="{{ $recent->photo }}" alt="{{ $recent->title }}" style="width:50px; height:50px; object-fit:cover; border-radius:6px;">
                                        @elseif($recent->photo && file_exists(public_path($recent->photo)))
                                            <img src="{{ asset($recent->photo) }}" alt="{{ $recent->title }}" style="width:50px; height:50px; object-fit:cover; border-radius:6px;">
                                        @else
                                            <img src="{{ asset('frontend/img/default.jpg') }}" alt="default" style="width:50px; height:50px; object-fit:cover; border-radius:6px;">
                                        @endif
                                    </div>
                                    <div class="recent-post-info">
                                        <div class="recent-post-title">
                                            <a href="{{ route('blog.detail', $recent->slug) }}">{{ $recent->title }}</a>
                                        </div>
                                        <div class="recent-post-date">
                                            {{ \Carbon\Carbon::parse($recent->created_at)->format('d M, y') }}
                                            • {{ $recent->author_info->name ?? 'Unknown' }}
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    @else
                        <li><em>Không tìm thấy bài đăng gần đây.</em></li>
                    @endif
                </ul>
            </div>

            <div class="sidebar-section">
                <h3 class="sidebar-title">Thẻ</h3>
                <div class="tag-cloud">
                    @php
                        // Lấy thẻ từ bảng post_tags
                        $sidebarTags = \App\Models\PostTag::where('status', 'active')->orderBy('title', 'ASC')->get();
                    @endphp
                    @if($sidebarTags->count())
                        @foreach($sidebarTags as $tag)
                            <a href="{{ route('blog.tag', $tag->slug) }}" class="tag">{{ $tag->title }}</a>
                        @endforeach
                    @else
                        <span><em>Không tìm thấy thẻ.</em></span>
                    @endif
                </div>
            </div>
        </aside>
    </div>

    <script>
        // Add hover effects and interactions
        document.querySelectorAll('.post-card').forEach(card => {
            card.addEventListener('click', function() {
                // Simulate navigation
                console.log('Navigate to post:', this.querySelector('.post-title').textContent);
            });
        });

        // Search functionality
        document.querySelector('.search-btn').addEventListener('click', function() {
            const searchTerm = document.querySelector('.search-box').value;
            if (searchTerm) {
                console.log('Searching for:', searchTerm);
                // Add search animation
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 100);
            }
        });

        // Newsletter subscription
        document.querySelector('.newsletter-btn').addEventListener('click', function() {
            const email = document.querySelector('.newsletter-input').value;
            if (email) {
                this.textContent = 'SUBSCRIBED!';
                this.style.background = '#27ae60';
                setTimeout(() => {
                    this.textContent = 'SUBMIT';
                    this.style.background = '#2c3e50';
                }, 2000);
            }
        });

        // Tag interactions
        document.querySelectorAll('.tag').forEach(tag => {
            tag.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Filter by tag:', this.textContent);
            });
        });

        // Submit comment via AJAX
        const commentForm = document.getElementById('commentForm');
        const commentContent = document.getElementById('commentContent');
        const commentCount = document.getElementById('commentCount');
        if (commentForm) {
            commentForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const content = commentContent.value.trim();
                if (content.length < 5) return;
                const formData = new URLSearchParams({ content });
                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                    },
                    body: formData.toString()
                }).then(r => r.json()).then(data => {
                    if (data && data.success) {
                        if (commentCount && typeof data.count !== 'undefined') commentCount.textContent = data.count;
                        // Prepend a simple rendered comment (client-side) for quick feedback
                        const list = document.getElementById('commentsList');
                        if (list) {
                            const wrapper = document.createElement('div');
                            wrapper.className = 'comment-item border-b border-gray-100 py-3';
                            wrapper.innerHTML = `
                              <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center text-sm font-semibold text-gray-600">{{ auth()->check() ? mb_strtoupper(mb_substr(auth()->user()->username, 0, 1)) : 'Ẩ' }}</div>
                                <div class="flex-1">
                                  <div class="text-sm text-gray-800 font-semibold">{{ auth()->check() ? e(auth()->user()->username) : 'Ẩn danh' }}</div>
                                  <div class="text-xs text-gray-500">Vừa xong</div>
                                  <div class="mt-1 text-[13px] leading-5 text-gray-800"></div>
                                </div>
                              </div>`;
                            wrapper.querySelector('.mt-1').textContent = content;
                            list.prepend(wrapper);
                        }
                        commentContent.value = '';
                        alert('Gửi bình luận thành công' + ({{ auth()->check() ? 'true' : 'false' }} ? '' : ', chờ duyệt'));
                    }
                }).catch(() => alert('Gửi bình luận thất bại'));
            })
        }

        // Post reactions
        document.querySelectorAll('.react-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const postId = this.getAttribute('data-id');
                const type = this.getAttribute('data-type');
                fetch(`{{ url('/blog') }}/${postId}/react`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: new URLSearchParams({ type })
                }).then(r => r.json()).then(data => {
                    if (data && data.by_type) {
                        for (const [type, val] of Object.entries(data.by_type)) {
                            const el = document.getElementById(`reaction-${type}-${postId}`);
                            if (el) el.textContent = val;
                        }
                        // Toggle active visual on current button
                        document.querySelectorAll(`.react-btn[data-id='${postId}']`).forEach(b => b.classList.remove('active'));
                        if (data.active_type) {
                            const activeBtn = document.querySelector(`.react-btn[data-id='${postId}'][data-type='${data.active_type}']`);
                            if (activeBtn) activeBtn.classList.add('active');
                        }
                    }
                }).catch(() => {
                    console.log('Reaction failed');
                });
            });
        });
    </script>
@endsection
