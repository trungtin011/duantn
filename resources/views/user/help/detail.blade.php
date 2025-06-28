@extends('layouts.app')

@section('title', $article->title)

@section('content')
<div class="container py-4">
    <h1>{{ $article->title }}</h1>
    <div class="mt-3">
        {!! $article->content !!}
    </div>
</div>
@endsection
