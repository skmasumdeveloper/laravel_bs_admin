@extends('layouts.app')

@section('title', $page->title)

@section('content')
    <div class="container py-5">
        <h1>{{ $page->title }}</h1>

        <div class="mt-4">
            {!! $page->content !!}
        </div>
    </div>
@endsection
