<x-layouts.app :title="__('Roles')">
    <div class="container py-4">
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h1 class="h5 mb-0">Roles</h1>
                    <p class="small text-muted mb-0">Create and manage user roles.</p>
                </div>
            </div>

            <div id="alert-container"></div>

            <div class="row">
                <div class="col-lg-5 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">{{ __('Create Role') }}</h6>

                            <form id="role-form">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Name') }}</label>
                                    <input id="role-name" name="name" class="form-control" />
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Permissions') }}</label>
                                    <div>
                                        @foreach ($availablePermissions as $permission)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission }}" id="perm-{{ Str::slug($permission) }}">
                                                <label class="form-check-label" for="perm-{{ Str::slug($permission) }}">{{ $permission }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                                    <button type="button" id="btn-reset-role" class="btn btn-outline-secondary">{{ __('Reset') }}</button>
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
                                            <th>{{ __('Role') }}</th>
                                            <th>{{ __('Permissions') }}</th>
                                            <th class="text-end">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody id="roles-table-body">
                                        @foreach ($roles as $role)
                                            <tr data-id="{{ $role->id }}">
                                                <td class="fw-semibold">{{ $role->name }}</td>
                                                <td>
                                                    @if($role->permissions->isNotEmpty())
                                                        @foreach($role->permissions as $perm)
                                                            <span class="badge bg-light border me-1">{{ $perm->name }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted small">{{ __('No permissions') }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <button class="btn btn-sm btn-link text-danger btn-delete-role" data-id="{{ $role->id }}">{{ __('Delete') }}</button>
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
            function showAlert(message, type = 'success') {
                const alertHtml = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;
                $('#alert-container').html(alertHtml);
                setTimeout(() => $('.alert').alert('close'), 5000);
            }

            function resetForm() {
                $('#role-form')[0].reset();
                $('.invalid-feedback').text('').hide();
                $('.form-control').removeClass('is-invalid');
            }

            $('#btn-reset-role').click(function() {
                resetForm();
            });

            $('#role-form').submit(function(e) {
                e.preventDefault();
                
                const formData = $(this).serializeArray().reduce((obj, item) => {
                    if (item.name === 'permissions[]') {
                        if (!obj.permissions) obj.permissions = [];
                        obj.permissions.push(item.value);
                    } else {
                        obj[item.name] = item.value;
                    }
                    return obj;
                }, {});

                $.ajax({
                    url: '{{ route('admin.roles.store') }}',
                    method: 'POST',
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
                                const input = $(`#role-${field}`);
                                input.addClass('is-invalid');
                                input.siblings('.invalid-feedback').text(errors[field][0]).show();
                            }
                        } else {
                            showAlert('An error occurred', 'danger');
                        }
                    }
                });
            });

            $(document).on('click', '.btn-delete-role', function() {
                if (!confirm('{{ __('Are you sure you want to delete this role?') }}')) {
                    return;
                }

                const id = $(this).data('id');

                $.ajax({
                    url: '{{ route('admin.roles.destroy', ':id') }}'.replace(':id', id),
                    method: 'DELETE',
                    success: function(response) {
                        showAlert(response.message, 'success');
                        $(`tr[data-id="${id}"]`).fadeOut(300, function() {
                            $(this).remove();
                        });
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            showAlert(xhr.responseJSON.message, 'danger');
                        } else {
                            showAlert('An error occurred', 'danger');
                        }
                    }
                });
            });

            $('.form-control').on('input', function() {
                $(this).removeClass('is-invalid');
                $(this).siblings('.invalid-feedback').text('').hide();
            });
        });
    </script>
    @endpush
</x-layouts.app>
