<x-layouts.app :title="$page->exists ? __('Edit Page') : __('Create Page')">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <p class="text-uppercase small text-muted mb-1">{{ __('Content management') }}</p>
                <h1 class="h3 mb-0">{{ $page->exists ? __('Edit Page') : __('Create Page') }}</h1>
            </div>
            <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary">{{ __('Back to list') }}</a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm border">
            <form action="{{ $page->exists ? route('admin.pages.update', $page) : route('admin.pages.store') }}" method="POST" class="card-body">
                @csrf
                @if($page->exists)
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label class="form-label">{{ __('Title') }}</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $page->title) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('Slug') }}</label>
                    <input type="text" name="slug" class="form-control" value="{{ old('slug', $page->slug) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('Content (HTML allowed)') }}</label>
                    <textarea id="page-content" name="content" rows="10" class="form-control">{{ old('content', $page->content) }}</textarea>
                </div>

                <div class="form-check form-switch mb-4">
                    <input type="checkbox" name="is_published" id="is_published" class="form-check-input" {{ old('is_published', $page->is_published) ? 'checked' : '' }}>
                    <label for="is_published" class="form-check-label">{{ __('Published') }}</label>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-link">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
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
                    toolbar: 'undo redo | formatselect | bold italic backcolor | ' +
                        'alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
                    setup(editor) {
                        editor.on('change', function () { editor.save(); });
                    }
                });
            }
        });
    </script>
</x-layouts.app>
