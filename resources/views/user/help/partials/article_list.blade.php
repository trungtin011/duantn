@if($category->articles->count())
    <ul class="faq-list">
        @foreach($category->articles as $article)
            <li class="faq-item">
                <div class="faq-text">
                    <a href="{{ route('help.detail', $article->slug) }}">
                        {{ $article->title }}
                    </a>
                </div>
                <!-- <div class="mt-3">
                    {!! $article->content !!}
                </div> -->
            </li>
        @endforeach 
    </ul>
@else
    <p>Không có bài viết nào trong danh mục này.</p>
@endif
