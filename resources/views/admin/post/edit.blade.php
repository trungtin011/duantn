@extends('layouts.admin')

@section('content')
    <div class="card">
        <h5 class="card-header">Edit Post</h5>
        <div class="card-body">
            <form action="{{ route('post.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Title --}}
                <div class="form-group">
                    <label for="inputTitle" class="col-form-label">Tiêu đề <span class="text-danger">*</span></label>
                    <input id="inputTitle" type="text" name="title" placeholder="Nhập tiêu đề" value="{{old('title')}}"
                        class="form-control">
                    @error('title')ass="
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Quote --}}
                <div class="form-group">
                    label for="quote" class="col-form-label">Trích dẫn</label>
                    <textarea class="form-control summernote-short" id="quote"
                        name="quote">{{ old('quote', $post->quote) }}</textarea>
                    @error('quote')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Summary --}}
                <div class="form-group">
                    <label for="summary" class="col-form-label">Bản tóm tắt <span class="text-danger">*</span></label>
                    <textarea class="form-control summernote-short" id="summary"
                        name="summary">{{ old('summary', $post->summary) }}</textarea>
                    @error('summary')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="form-group">
                    <label for="description" class="col-form-label">Miêu tả nội dung </label>
                    <textarea class="form-control summernote" id="description"
                        name="description">{{ old('description', $post->description) }}</textarea>
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Category --}}
                <div class="form-group">
                    <label for="post_cat_id">Loại<span class="text-danger">*</span></label>
                    <select name="post_cat_id" class="form-control">
                        <option value="">--Chọn bất kỳ doanh mục nào-</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('post_cat_id', $post->post_cat_id) == $cat->id ? 'selected' : '' }}>{{ $cat->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tags --}}
                @php
                    $selectedTags = explode(',', old('tags', $post->tags));
                @endphp
                <div class="form-group">
                    <label for="tags">Tags</label>
                    <select name="tags[]" multiple data-live-search="true" class="form-control selectpicker">
                        <option value="">--Chọn bất kỳ tag--</option>
                        @foreach($tags as $tag)
                            <option value="{{ $tag->title }}" {{ in_array($tag->title, $selectedTags) ? 'selected' : '' }}>
                                {{ $tag->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Author --}}
                <div class="form-group">
                    <label for="added_by">Tác giả</label>
                    <select name="added_by" class="form-control">
                        <option value="">--Chọn bất kỳ tác giả--</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('added_by', $post->added_by) == $user->id ? 'selected' : '' }}>
                                {{ $user->username }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Photo Upload --}}
                <div class="bg-white p-3 rounded-lg shadow-sm mb-4">
                    <p class="font-weight-bold">Tải ảnh chính lên</p>
                    @if($post->photo)
                        <div class="mb-2">
                            <img src="{{ asset($post->photo) }}" id="uploadIcon1" class="w-25" alt="current photo">
                        </div>
                    @endif
                    <label for="mainImage" class="btn btn-outline-secondary d-block text-center">
                        Chọn ảnh mới
                    </label>
                    <input type="file" id="mainImage" name="photo" class="d-none" accept="image/*">
                    @error('photo')
                        <span class="text-danger d-block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="form-group">
                    <label for="status">Trạng thái <span class="text-danger">*</span></label>
                    <select name="status" class="form-control">
                        <option value="active" {{ old('status', $post->status) == 'active' ? 'selected' : '' }}>Active
                        </option>
                        <option value="inactive" {{ old('status', $post->status) == 'inactive' ? 'selected' : '' }}>Inactive
                        </option>
                    </select>
                    @error('status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="form-group mb-3">
                    <button type="reset" class="btn btn-warning">Reset</button>
                    <button class="btn btn-success" type="submit">Update</button>
                </div>
            </form>
        </div>
    </div>
    <!-- jQuery (cần cho Summernote) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Summernote JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.summernote-short').summernote({
                height: 150,
                minHeight: 100,
                maxHeight: 100,
                placeholder: 'Nhập nội dung ngắn...',
                toolbar: [
                    ['font', ['bold', 'italic', 'underline']],
                    ['para', ['ul', 'ol']],
                    ['view', ['codeview']]
                ]
            });
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
