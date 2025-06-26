@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">Sửa bài viết</div>
        <div class="card-body">
            <form action="{{ route('help-article.update', $article->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>Tiêu đề</label>
                    <input type="text" name="title" class="form-control" value="{{ $article->title }}" required>
                </div>
                <div class="form-group">
                    <label>Danh mục</label>
                    <select name="category_id" class="form-control" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $article->category_id == $cat->id ? 'selected' : '' }}>
                                {{ $cat->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Nội dung</label>
                    <textarea name="content" class="form-control summernote" required>{!! $article->content !!}</textarea>
                </div>

                <div class="form-group">
                    <label>Trạng thái</label>
                    <select name="status" class="form-control">
                        <option value="active" {{ $article->status == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ $article->status == 'inactive' ? 'selected' : '' }}>Ẩn</option>
                    </select>
                </div>
                <button class="btn btn-primary">Cập nhật</button>
            </form>
        </div>
    </div>
    <!-- jQuery (cần cho Summernote) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Summernote JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.summernote').summernote({
                height: 250,
                placeholder: 'Nhập nội dung bài viết...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>

@endsection
