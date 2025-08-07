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
            @foreach($posts as $post)
                <article class="post-card">
                    <div class="post-image food"><img src="{{$post->photo}}" alt="{{$post->photo}}"></div>
                    <div class="post-content">
                        <div class="post-meta">
                            {{$post->created_at->format('d M, Y. D')}}
                            <span class="float-right">
                                <i class="fa fa-user" aria-hidden="true"></i>
                                {{$post->author_info->username ?? 'Anonymous'}}
                            </span>
                        </div>
                        <h2 href="{{route('blog.detail', $post->slug)}}" class="post-title">{{$post->title}}</h2>
                        <p class="post-excerpt">{{ Str::limit(strip_tags($post->summary), 150) }}</p>

                        <a href="{{route('blog.detail', $post->slug)}}" class="continue-reading">Tiếp tục đọc</a>
                    </div>
                </article>
            @endforeach


            <article class="post-card">
                <div class="post-image shopping"></div>
                <div class="post-content">
                    <div class="post-meta">
                        <span>16 Aug, 2020, Tue</span>
                        <span>Projwal Rai</span>
                    </div>
                    <h2 class="post-title">Lorem Ipsum is simply</h2>
                    <p class="post-excerpt">Lorem ipsum is simply dummy text of the printing and typesetting industry...</p>
                    <a href="#" class="continue-reading">Continue Reading</a>
                </div>
            </article>

            <article class="post-card">
                <div class="post-image fashion"></div>
                <div class="post-content">
                    <div class="post-meta">
                        <span>15 Aug, 2020, Sat</span>
                        <span>Projwal Rai</span>
                    </div>
                    <h2 class="post-title">The standard Lorem Ipsum passage</h2>
                    <p class="post-excerpt">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                        incididunt...</p>
                    <a href="#" class="continue-reading">Continue Reading</a>
                </div>
            </article>

            <article class="post-card">
                <div class="post-image lifestyle"></div>
                <div class="post-content">
                    <div class="post-meta">
                        <span>14 Aug, 2020, Fri</span>
                        <span>Projwal Rai</span>
                    </div>
                    <h2 class="post-title">The standard Lorem Ipsum passage, used since the 1500s</h2>
                    <p class="post-excerpt">Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
                    <a href="#" class="continue-reading">Continue Reading</a>
                </div>
            </article>

            <article class="post-card">
                <div class="post-image shopping"></div>
                <div class="post-content">
                    <div class="post-meta">
                        <span>14 Aug, 2020, Fri</span>
                        <span>Projwal Rai</span>
                    </div>
                    <h2 class="post-title">Where can I get some?</h2>
                    <p class="post-excerpt">It is a long established fact that a reader will be distracted by the readable
                        content...</p>
                    <a href="#" class="continue-reading">Continue Reading</a>
                </div>
            </article>
        </main>

        <aside class="sidebar">
            <div class="sidebar-section">
                <div style="display: flex; gap: 10px;">
                    <form class="form" method="GET" action="{{route('blog.search')}}"></form>
                    <input type="text" class="search-box" placeholder="Tìm kiếm ở đây...">
                    <button class="search-btn"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </form>
                </div>
            </div>

            <div class="sidebar-section">
                <h3 class="sidebar-title">Thể loại bài viết</h3>
                <ul class="category-list">
                    @if(!empty($_GET['category']))
                        @php
                            $filter_cats = explode(',', $_GET['category']);
                        @endphp
                    @endif
                    <form action="{{route('blog.filter')}}" method="POST">
                        @if(!empty($_GET['category']))
                            @php
                                $filter_cats = explode(',', $_GET['category']);
                            @endphp
                        @endif
                        <form action="{{route('blog.filter')}}" method="POST">
                            @csrf
                            {{-- {{count(Helper::postCategoryList())}} --}}
                            @foreach($categories as $cat)
                                <li>
                                    <a href="{{route('blog.category', $cat->slug)}}">{{$cat->title}} </a>
                                </li>
                            @endforeach
                        </form>
                </ul>
            </div>

            <div class="sidebar-section">
                <h3 class="sidebar-title">Bài viết gần đây </h3>
                @foreach($recent_posts as $post)
                    <ul class="recent-posts">
                        <li>
                            <div class="recent-post-item">
                                <div class="image"><img src="{{$post->photo}}" alt="{{$post->photo}}">
                                </div>
                                <div class="recent-post-info">
                                    <div class="recent-post-title">{{$post->title}}</div>
                                    <div class="recent-post-date">{{$post->created_at->format('d M, y')}} •
                                        {{$post->author_info->username ?? 'Anonymous'}}</div>
                                </div>
                            </div>
                        </li>
                    </ul>
                @endforeach
            </div>

            <div class="sidebar-section">
                <h3 class="sidebar-title">Tags</h3>
                <div class="tag-cloud mb-3">
                    <a href="#" class="tag">Tag</a>
                    <a href="#" class="tag">Visit Nepal 2020</a>
                    <a href="#" class="tag">2020</a>
                    <a href="#" class="tag">Enjoy</a>
                </div>
                <ul class="tag">
                    @if(!empty($_GET['tag']))
                        @php
                            $filter_tags = explode(',', $_GET['tag']);
                        @endphp
                    @endif
                    <form action="{{route('blog.filter')}}" method="POST">
                        @csrf
                        @foreach($tags as $tag)
                            <li>
                            <li>
                                <a href="{{route('blog.tag', $tag->title)}}">{{$tag->title}} </a>
                            </li>
                            </li>
                        @endforeach
                    </form>
                </ul>
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
