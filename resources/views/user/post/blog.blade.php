@extends('layouts.app')
@push('styles')
    @vite('resources/css/user/post.css')
@endpush
@section('title', 'Bài viết')
@section('content')
    <div class="main-banner">
        <div class="main-banner-content">
            <img src="{{ asset('assets/images/banner-1.jpg') }}" alt="Blog Banner" class="main-banner-image">
            <div class="main-banner-info">
                <h1 class="main-banner-title">Bài viết</h1>
                <div class="breadcrumb">
                    <a href="{{ route('home') }}">Trang chủ</a>
                    <span>Bài viết</span>
                </div>
            </div>
        </div>
    </div>
    <div class="container_blog">
        <main class="main-content">
            @forelse($posts as $post)
                <article class="post-card">
                    <div class="post-image">
                        @php
                            $img = $post->photo && file_exists(public_path($post->photo)) ? asset($post->photo) : ($post->photo && filter_var($post->photo, FILTER_VALIDATE_URL) ? $post->photo : asset('frontend/img/default.jpg'));
                        @endphp
                        <img src="{{ $img }}" alt="{{ $post->title }}">
                    </div>
                    <div class="post-content">
                        <div class="post-meta">
                            {{ $post->created_at->format('d M, Y. D') }}
                            <span class="float-right">
                                <i class="fa fa-user" aria-hidden="true"></i>
                                {{ $post->author_info->username ?? 'Ẩn danh' }}
                            </span>
                        </div>
                        <h2 class="post-title"><a href="{{ route('blog.detail', $post->slug) }}">{{ $post->title }}</a></h2>
                        <p class="post-excerpt">{{ Str::limit(strip_tags($post->summary), 150) }}</p>
                        <a href="{{ route('blog.detail', $post->slug) }}" class="continue-reading">Đọc tiếp</a>
                    </div>
                </article>
            @empty
                <p class="text-gray-500">Chưa có bài viết nào.</p>
            @endforelse

            @if(method_exists($posts, 'links'))
                <div class="mt-6">
                    {{ $posts->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </main>

        <aside class="sidebar">
            <div class="sidebar-section">
                <form method="GET" action="{{ route('blog.search') }}" style="display:flex; gap:10px; width:100%">
                    <input type="text" class="search-box" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm bài viết..." aria-label="Tìm kiếm" style="flex:1">
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
                    @forelse($categories as $cat)
                        <li>
                            <a href="{{ route('blog.category', $cat->slug) }}">{{ $cat->title }}</a>
                        </li>
                    @empty
                        <li><em>Không có danh mục.</em></li>
                    @endforelse
                </ul>
            </div>

            <div class="sidebar-section">
                <h3 class="sidebar-title">Bài viết gần đây</h3>
                <ul class="recent-posts">
                    @forelse($recent_posts as $rp)
                        @php
                            $rpImg = $rp->photo && file_exists(public_path($rp->photo)) ? asset($rp->photo) : ($rp->photo && filter_var($rp->photo, FILTER_VALIDATE_URL) ? $rp->photo : asset('frontend/img/default.jpg'));
                        @endphp
                        <li>
                            <div class="recent-post-item">
                                <div class="recent-post-thumb"><img src="{{ $rpImg }}" alt="{{ $rp->title }}"></div>
                                <div class="recent-post-info">
                                    <div class="recent-post-title">{{ $rp->title }}</div>
                                    <div class="recent-post-date">{{ $rp->created_at->format('d M, y') }} • {{ $rp->author_info->username ?? 'Ẩn danh' }}</div>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li><em>Không có bài viết gần đây.</em></li>
                    @endforelse
                </ul>
            </div>

            <div class="sidebar-section">
                <h3 class="sidebar-title">Thẻ</h3>
                <div class="tag-cloud mb-3">
                    @forelse($tags as $tag)
                        <a href="{{ route('blog.tag', $tag->title) }}" class="tag">{{ $tag->title }}</a>
                    @empty
                        <span><em>Không có thẻ.</em></span>
                    @endforelse
                </div>
            </div>
        </aside>
    </div>
    <script>
        // Add hover effects and interactions
        document.querySelectorAll('.post-card').forEach(card => {
            card.addEventListener('click', function () {
                // Simulate navigation
                console.log('Navigate to post:', this.querySelector('.post-title').textContent);
            });
        });

        // Search functionality
        document.querySelector('.search-btn').addEventListener('click', function () {
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
        document.querySelector('.newsletter-btn').addEventListener('click', function () {
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
            tag.addEventListener('click', function (e) {
                e.preventDefault();
                console.log('Filter by tag:', this.textContent);
            });
        });
    </script>
@endsection
