<x-layouts.app :title="__('Categories')">
    <div class="container py-4">
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h1 class="h5 mb-0">Categories</h1>
                    <p class="small text-muted mb-0">Create and organize categories and sub-categories.</p>
                </div>

                <div>
                    <button id="btn-new-category" class="btn btn-primary">New Category</button>
                </div>
            </div>

            <div id="alert-container"></div>

            <div class="row">
                <div class="col-lg-5 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title" id="form-title">{{ __('Create Category') }}</h6>

                            <form id="category-form">
                                <input type="hidden" id="category-id" name="id">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Name') }}</label>
                                    <input id="category-name" name="name" class="form-control" />
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Parent') }}</label>
                                    <select id="category-parent" name="parent_id" class="form-select">
                                        <option value="">— {{ __('None') }} —</option>
                                        @foreach ($allCategories as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                                    <button type="button" id="btn-reset" class="btn btn-outline-secondary">{{ __('Reset') }}</button>
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
                                            <th>{{ __('Category') }}</th>
                                            <th class="text-end">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody id="categories-table-body">
                                        @foreach ($categories as $cat)
                                            <tr data-id="{{ $cat->id }}">
                                                <td>
                                                    <div class="fw-semibold">{{ $cat->name }}</div>
                                                    @if($cat->children->isNotEmpty())
                                                        <div class="small text-muted">
                                                            @foreach($cat->children as $child)
                                                                <span class="badge bg-light border me-1">{{ $child->name }}</span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <button class="btn btn-sm btn-link btn-edit" data-id="{{ $cat->id }}" data-name="{{ $cat->name }}" data-parent="{{ $cat->parent_id }}">{{ __('Edit') }}</button>
                                                    <button class="btn btn-sm btn-link text-danger btn-delete" data-id="{{ $cat->id }}">{{ __('Delete') }}</button>
                                                </td>
                                            </tr>
                                        @endforeach
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

            // Show alert
            function showAlert(message, type = 'success') {
                const alertHtml = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;
                $('#alert-container').html(alertHtml);
                setTimeout(() => $('.alert').alert('close'), 5000);
            }

            // Reset form
            function resetForm() {
                $('#category-form')[0].reset();
                $('#category-id').val('');
                editingId = null;
                $('#form-title').text('{{ __('Create Category') }}');
                $('.invalid-feedback').text('').hide();
                $('.form-control, .form-select').removeClass('is-invalid');
            }

            // New category button
            $('#btn-new-category, #btn-reset').click(function() {
                resetForm();
            });

            // Edit category
            $(document).on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const parent = $(this).data('parent');
                
                editingId = id;
                $('#category-id').val(id);
                $('#category-name').val(name);
                $('#category-parent').val(parent || '');
                $('#form-title').text('{{ __('Edit Category') }}');
            });

            // Submit form
            $('#category-form').submit(function(e) {
                e.preventDefault();
                
                const formData = {
                    name: $('#category-name').val(),
                    parent_id: $('#category-parent').val() || null
                };

                const url = editingId 
                    ? '{{ route('admin.categories.update', ':id') }}'.replace(':id', editingId)
                    : '{{ route('admin.categories.store') }}';
                
                const method = editingId ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    success: function(response) {
                        showAlert(response.message, 'success');
                        resetForm();
                        location.reload(); // Reload to update the table
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            for (let field in errors) {
                                const input = $(`#category-${field.replace('_', '-')}`);
                                input.addClass('is-invalid');
                                input.siblings('.invalid-feedback').text(errors[field][0]).show();
                            }
                        } else {
                            showAlert('An error occurred', 'danger');
                        }
                    }
                });
            });

            // Delete category
            $(document).on('click', '.btn-delete', function() {
                if (!confirm('{{ __('Are you sure you want to delete this category?') }}')) {
                    return;
                }

                const id = $(this).data('id');
                const url = '{{ route('admin.categories.destroy', ':id') }}'.replace(':id', id);

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

            // Clear validation on input
            $('.form-control, .form-select').on('input change', function() {
                $(this).removeClass('is-invalid');
                $(this).siblings('.invalid-feedback').text('').hide();
            });
        });
    </script>
    @endpush
</x-layouts.app>
