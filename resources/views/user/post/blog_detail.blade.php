@extends('layouts.app')
@push('styles')
    @vite('resources/css/user/post.css')
@endpush
@section('title', 'Bài viết')
@section('content')
    <div class="container_blog_detail">

        <main class="main-content_detail">
            <div class="mainProductImage">
                <img style="height: 450px" src="{{ asset($post->photo) }}" alt="{{ $post->title }}">
            </div>

            <div class="post-header">
                <h1>{{ $post->title }}</h1>
                <div class="post-meta">
                    <span>👤 By {{ $post->author_info->username ?? 'Anonymous' }}</span>
                    <span>📅 {{ $post->created_at->format('d M, Y. D') }}</span>
                    <span>💬 Comment ({{ $post->comments->count() }})</span>
                </div>
            </div>

            <div class="reactions">
                <div class="reaction">
                    <div class="reaction-emoji">😊</div>
                    <div class="reaction-count">{{ $post->likes->count() }}</div>
                </div>
                <div class="reaction">
                    <div class="reaction-emoji">😍</div>
                    <div class="reaction-count">{{ $post->likes->count() }}</div>
                </div>
                <div class="reaction">
                    <div class="reaction-emoji">😄</div>
                    <div class="reaction-count">{{ $post->likes->count() }}</div>
                </div>
                <div class="reaction">
                    <div class="reaction-emoji">😮</div>
                    <div class="reaction-count">{{ $post->likes->count() }}</div>
                </div>
                <div class="reaction">
                    <div class="reaction-emoji">😂</div>
                    <div class="reaction-count">{{ $post->likes->count() }}</div>
                </div>
                <div class="reaction">
                    <div class="reaction-emoji">😠</div>
                    <div class="reaction-count">{{ $post->likes->count() }}</div>
                </div>
            </div>

            <div class="content-section">
                @if ($post->quote)
                    <blockquote> <i class="fa fa-quote-left"></i> {!! $post->quote !!}</blockquote>
                @endif
                <p>{!! $post->description !!}</p>
            </div>

            <div class="tags">
                <h4>Tags:</h4>
                <ul class="tag-inner">
                    @php
                        $tags = explode(',', $post->tags);
                    @endphp
                    @foreach ($tags as $tag)
                        <li><a href="javascript:void(0);">{{ $tag }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="comment-section">
                <h3>Leave A Comment</h3>
                <form class="comment-form">
                    <textarea placeholder="Your Message *" required></textarea>
                    <button type="submit" class="btn">POST COMMENT</button>
                </form>
                <h4 style="margin-top: 20px;">Comments (0)</h4>
            </div>
        </main>

        <aside class="sidebar">
            <div class="sidebar-section">
                <div style="display: flex; gap: 10px;">
                    <input type="text" class="search-box" placeholder="Search Here...">
                    <button class="search-btn">🔍</button>
                </div>
            </div>

            <div class="sidebar-section">
                <h3 class="sidebar-title">Blog Categories</h3>
                <ul class="category-list">
                    <li><a href="#">Post Category</a></li>
                    <li><a href="#">enjoy</a></li>
                    <li><a href="#">Cloths</a></li>
                    <li><a href="#">Electronics</a></li>
                    <li><a href="#">Travel</a></li>
                </ul>
            </div>

            <div class="sidebar-section">
                <h3 class="sidebar-title">Recent Post</h3>
                <ul class="recent-posts">
                    <li>
                        <div class="recent-post-item">
                            <div class="recent-post-thumb"></div>
                            <div class="recent-post-info">
                                <div class="recent-post-title">Delicious Food Collection</div>
                                <div class="recent-post-date">18 Jun, 25 • huy pham</div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="recent-post-item">
                            <div class="recent-post-thumb" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                            </div>
                            <div class="recent-post-info">
                                <div class="recent-post-title">Lorem Ipsum is simply</div>
                                <div class="recent-post-date">18 Aug, 20 • Projwal Rai</div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="recent-post-item">
                            <div class="recent-post-thumb" style="background: linear-gradient(135deg, #43e97b, #38f9d7);">
                            </div>
                            <div class="recent-post-info">
                                <div class="recent-post-title">The standard Lorem Ipsum passage</div>
                                <div class="recent-post-date">15 Aug, 20 • Projwal Rai</div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="sidebar-section">
                <h3 class="sidebar-title">Tags</h3>
                <div class="tag-cloud">
                    <a href="#" class="tag">Tag</a>
                    <a href="#" class="tag">Visit Nepal 2020</a>
                    <a href="#" class="tag">2020</a>
                    <a href="#" class="tag">Enjoy</a>
                </div>
            </div>

            <div class="sidebar-section newsletter">
                <h3 class="sidebar-title">Newsletter</h3>
                <p style="margin-bottom: 20px; color: #666;">Subscribe & Get News Latest Updates.</p>
                <input type="email" class="newsletter-input" placeholder="Enter your email">
                <button class="newsletter-btn">SUBMIT</button>
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
    </script>
@endsection
