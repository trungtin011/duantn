@extends('layouts.admin')
@section('content')
    <h2>Post List</h2>
    <a href="{{ route('post.create') }}" class="btn btn-success mb-3">Add New</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Category</th>
                <th>Tag</th>
                <th>Author</th>
                <th>Photo</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $post)
                        @php
                            $author = DB::table('users')->select('fullname', 'username')->where('id', $post->added_by)->first();
                        @endphp
                        <tr>
                            <td>{{ $post->id }}</td>
                            <td>{{ $post->title }}</td>
                            <td>{{ $post->cat_info->title ?? 'N/A' }}</td>
                            <td>{{ $post->tags ?? 'N/A' }}</td>
                            <td>{{ $author->username }}</td>

                            <td>
                                @if($post->photo)
                                    <img src="{{ asset($post->photo) }}" style="max-height:80px;">
                                @else
                                    <img src="{{ asset('backend/img/thumbnail-default.jpg') }}" class="img-fluid" style="max-width:80px"
                                        alt="default">
                                @endif
                            </td>
                            <td>
                                @if($post->status == 'active')
                                    <span>{{$post->status}}</span>
                                @else
                                    <span></span>{{$post->status}}</span>
                                @endif

                            </td>
                            <!-- <pre>
                    {{ print_r($posts->toArray(), true) }}
                </pre> -->

                            <td>
                                <a href="{{ route('post.edit', $post->id) }}" class="btn btn-primary btn-sm mr-1"
                                    style="height:30px; width:30px; border-radius:50%" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('post.destroy', $post->id) }}" style="display:inline;">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-danger btn-sm dltBtn" style="height:30px; width:30px; border-radius:50%"
                                        title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
            @endforeach
        </tbody>
    </table>

    {{ $posts->links() }}



@endsection
