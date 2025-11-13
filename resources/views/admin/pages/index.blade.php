@extends('layouts.app')

@section('title', 'Pages')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Pages</h1>
            <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">Create Page</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Published</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($pages as $p)
                    <tr>
                        <td>{{ $p->id }}</td>
                        <td>{{ $p->title }}</td>
                        <td>{{ $p->slug }}</td>
                        <td>{{ $p->is_published ? 'Yes' : 'No' }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.pages.edit', $p) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form action="{{ route('admin.pages.destroy', $p) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this page?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $pages->links() }}
    </div>
@endsection
