@extends('layouts.app')

@section('title', $page->exists ? 'Edit Page' : 'Create Page')

@section('content')
    <div class="container py-5">
        <h1>{{ $page->exists ? 'Edit Page' : 'Create Page' }}</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ $page->exists ? route('admin.pages.update', $page) : route('admin.pages.store') }}" method="POST">
            @csrf
            @if($page->exists)
                @method('PUT')
            @endif

            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $page->title) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug', $page->slug) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Content (HTML allowed)</label>
                <textarea id="page-content" name="content" rows="10" class="form-control">{{ old('content', $page->content) }}</textarea>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="is_published" id="is_published" class="form-check-input" {{ old('is_published', $page->is_published) ? 'checked' : '' }}>
                <label for="is_published" class="form-check-label">Published</label>
            </div>

            <button class="btn btn-primary">Save</button>
            <a href="{{ route('admin.pages.index') }}" class="btn btn-link">Cancel</a>
        </form>
    </div>
    {{-- TinyMCE WYSIWYG editor (CDN) --}}
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof tinymce !== 'undefined') {
                tinymce.init({
                    selector: '#page-content',
                    height: 400,
                    menubar: false,
                    plugins: [
                        'advlist autolink lists link image charmap preview anchor',
                        'searchreplace visualblocks code fullscreen',
                        'insertdatetime media table paste code help wordcount'
                    ],
                    toolbar: 'undo redo | formatselect | bold italic backcolor | \n' +
                        'alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
                    content_css: [],
                    setup: function (editor) {
                        // Prevent form submission issues by syncing content
                        editor.on('change', function () { editor.save(); });
                    }
                });
            }
        });
    </script>
@endsection
