@extends('layouts.admin')

@section('content')
    <div class="card">
        <h5 class="card-header">Thêm bài viết</h5>
        <div class="card-body">
            <form action="{{ route('post.store') }}" method="POST" enctype="multipart/form-data">

                {{csrf_field()}}
                <div class="form-group">
                    <label for="inputTitle" class="col-form-label">Tiêu đề <span class="text-danger">*</span></label>
                    <input id="inputTitle" type="text" name="title" placeholder="Nhập tiêu đề" value="{{old('title')}}"
                        class="form-control">
                    @error('title')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="quote" class="col-form-label">Trích dẫn</label>
                    <textarea class="form-control summernote-short" id="quote" name="quote">{{ old('quote') }}</textarea>
                    @error('quote')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="summary" class="col-form-label">Bản tóm tắt <span class="text-danger">*</span></label>
                    <textarea class="form-control summernote-short" id="summary" name="summary">{{ old('summary') }}</textarea>
                    @error('summary')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description" class="col-form-label">Miêu tả nội dung </label>
                    <textarea class="form-control summernote" id="description"
                        name="description">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="post_cat_id">Loại<span class="text-danger">*</span></label>
                    <select name="post_cat_id" class="form-control">
                        <option value="">--Chọn bất kỳ doanh mục nào--</option>
                        @foreach($categories as $key => $data)
                            <option value='{{$data->id}}'>{{$data->title}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="tags">Tag</label>
                    <select name="tags[]" multiple data-live-search="true" class="form-control selectpicker">
                        <option value="">--Chọn bất kỳ tag--</option>
                        @foreach($tags as $key => $data)
                            <option value='{{$data->title}}'>{{$data->title}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="added_by">Tác giả</label>
                    <select name="added_by" class="form-control">
                        <option value="">--Chọn bất kỳ one--</option>
                        @foreach($users as $key => $data)
                            <option value='{{$data->id}}' {{($key == 0) ? 'selected' : ''}}>{{$data->username}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                    <p class="text-gray-700 font-medium mb-4">Tải ảnh chính lên</p>
                    <div class="text-center">
                        <img id="uploadIcon1" class="w-24 h-auto mx-auto mb-2"
                            src="https://html.hixstudio.net/ebazer/assets/img/icons/upload.png" alt="Upload Icon">
                        <span class="text-sm text-gray-500 block mb-3">Kích thước ảnh phải nhỏ hơn 5Mb</span>
                        <label for="mainImage"
                            class="block w-full py-2 px-4 border border-gray-300 rounded-md text-center text-sm text-gray-700 hover:bg-blue-50 cursor-pointer">
                            Tải ảnh chính lên
                        </label>
                        <input type="file" id="mainImage" name="photo" class="hidden" accept="image/*">
                        @error('photo')
                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                </div>



                <div class="form-group">
                    <label for="status" class="col-form-label">Trạng thái <span class="text-danger">*</span></label>
                    <select name="status" class="form-control">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    @error('status')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <button type="reset" class="btn btn-warning">Reset</button>
                    <button class="btn btn-success" type="submit">Submit</button>
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
