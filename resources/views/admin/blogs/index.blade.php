<x-layouts.app :title="__('Blog Posts')">
    <div class="container py-4">
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h1 class="h5 mb-0">Blog Posts</h1>
                    <p class="small text-muted mb-0">Create and manage blog posts.</p>
                </div>

                <div>
                    <button id="btn-new-blog" class="btn btn-primary">New Blog Post</button>
                </div>
            </div>

            <div id="alert-container"></div>

            <div class="row">
                <div class="col-lg-5 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title" id="form-title">{{ __('Create Blog Post') }}</h6>

                            <form id="blog-form">
                                <input type="hidden" id="blog-id" name="id">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Title') }}</label>
                                    <input id="blog-title" name="title" class="form-control" />
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Content') }}</label>
                                    <textarea id="blog-content" name="content" class="form-control" rows="5"></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Category') }}</label>
                                    <select id="blog-category" name="category_id" class="form-select">
                                        <option value="">— {{ __('None') }} —</option>
                                        @foreach ($categories as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Status') }}</label>
                                    <select id="blog-status" name="status" class="form-select">
                                        <option value="draft">{{ __('Draft') }}</option>
                                        <option value="published">{{ __('Published') }}</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                                    <button type="button" id="btn-reset-blog" class="btn btn-outline-secondary">{{ __('Reset') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Title') }}</th>
                                            <th>{{ __('Category') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th class="text-end">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody id="blogs-table-body">
                                        @forelse ($blogs as $blog)
                                            <tr data-id="{{ $blog->id }}">
                                                <td>
                                                    <div class="fw-semibold">{{ $blog->title }}</div>
                                                    @if($blog->content)
                                                        <div class="small text-muted">{{ Str::limit($blog->content, 50) }}</div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($blog->category)
                                                        <span class="badge bg-light border">{{ $blog->category->name }}</span>
                                                    @else
                                                        <span class="text-muted small">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($blog->status === 'published')
                                                        <span class="badge bg-success">{{ __('Published') }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ __('Draft') }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <button class="btn btn-sm btn-link btn-edit-blog" 
                                                        data-id="{{ $blog->id }}" 
                                                        data-title="{{ $blog->title }}" 
                                                        data-content="{{ $blog->content }}"
                                                        data-category="{{ $blog->category_id }}"
                                                        data-status="{{ $blog->status }}">{{ __('Edit') }}</button>
                                                    <button class="btn btn-sm btn-link text-danger btn-delete-blog" data-id="{{ $blog->id }}">{{ __('Delete') }}</button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-3">{{ __('No blog posts found.') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            let editingId = null;

            function showAlert(message, type = 'success') {
                const alertHtml = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;
                $('#alert-container').html(alertHtml);
                setTimeout(() => $('.alert').alert('close'), 5000);
            }

            function resetForm() {
                $('#blog-form')[0].reset();
                $('#blog-id').val('');
                editingId = null;
                $('#form-title').text('{{ __('Create Blog Post') }}');
                $('.invalid-feedback').text('').hide();
                $('.form-control, .form-select').removeClass('is-invalid');
            }

            $('#btn-new-blog, #btn-reset-blog').click(function() {
                resetForm();
            });

            $(document).on('click', '.btn-edit-blog', function() {
                const id = $(this).data('id');
                const title = $(this).data('title');
                const content = $(this).data('content');
                const category = $(this).data('category');
                const status = $(this).data('status');
                
                editingId = id;
                $('#blog-id').val(id);
                $('#blog-title').val(title);
                $('#blog-content').val(content);
                $('#blog-category').val(category || '');
                $('#blog-status').val(status);
                $('#form-title').text('{{ __('Edit Blog Post') }}');
            });

            $('#blog-form').submit(function(e) {
                e.preventDefault();
                
                const formData = {
                    title: $('#blog-title').val(),
                    content: $('#blog-content').val(),
                    category_id: $('#blog-category').val() || null,
                    status: $('#blog-status').val()
                };

                const url = editingId 
                    ? '{{ route('admin.blogs.update', ':id') }}'.replace(':id', editingId)
                    : '{{ route('admin.blogs.store') }}';
                
                const method = editingId ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    success: function(response) {
                        showAlert(response.message, 'success');
                        resetForm();
                        location.reload();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            for (let field in errors) {
                                const input = $(`#blog-${field.replace('_', '-')}`);
                                input.addClass('is-invalid');
                                input.siblings('.invalid-feedback').text(errors[field][0]).show();
                            }
                        } else {
                            showAlert('An error occurred', 'danger');
                        }
                    }
                });
            });

            $(document).on('click', '.btn-delete-blog', function() {
                if (!confirm('{{ __('Are you sure you want to delete this blog post?') }}')) {
                    return;
                }

                const id = $(this).data('id');
                const url = '{{ route('admin.blogs.destroy', ':id') }}'.replace(':id', id);

                $.ajax({
                    url: url,
                    method: 'DELETE',
                    success: function(response) {
                        showAlert(response.message, 'success');
                        $(`tr[data-id="${id}"]`).fadeOut(300, function() {
                            $(this).remove();
                        });
                    },
                    error: function() {
                        showAlert('An error occurred', 'danger');
                    }
                });
            });

            $('.form-control, .form-select').on('input change', function() {
                $(this).removeClass('is-invalid');
                $(this).siblings('.invalid-feedback').text('').hide();
            });
        });
    </script>
    @endpush
</x-layouts.app>
