<x-layouts.app :title="__('Users')">
    <div class="container py-4">
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h1 class="h5 mb-0">Users</h1>
                    <p class="small text-muted mb-0">Create, edit and manage users and their roles.</p>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <input id="search-input" type="search" class="form-control form-control-sm me-2" placeholder="Search users..." style="width:220px">
                    <button id="btn-new-user" class="btn btn-primary">{{ __('New User') }}</button>
                </div>
            </div>

            <div id="alert-container"></div>

            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="users-table-body">
                                @forelse ($users as $user)
                                    <tr data-id="{{ $user->id }}">
                                        <td class="fw-semibold">{{ $user->name }}</td>
                                        <td class="text-muted">{{ $user->email }}</td>
                                        <td>
                                            @foreach ($user->roles as $role)
                                                <span class="badge bg-secondary me-1">{{ $role->name }}</span>
                                            @endforeach
                                        </td>
                                        <td class="text-end">
                                            <button class="btn btn-sm btn-link btn-edit-user" 
                                                data-id="{{ $user->id }}"
                                                data-name="{{ $user->name }}"
                                                data-email="{{ $user->email }}"
                                                data-roles='@json($user->roles->pluck('name'))'>Edit</button>
                                            <button class="btn btn-sm btn-link text-danger btn-delete-user" data-id="{{ $user->id }}">Delete</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No users found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Create User') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="create-user-form">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Name') }}</label>
                            <input name="name" class="form-control" />
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Email') }}</label>
                            <input name="email" type="email" class="form-control" />
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Password') }}</label>
                            <input name="password" type="password" class="form-control" />
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Roles</label>
                            <div>
                                @foreach ($availableRoles as $role)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role }}" id="create-role-{{ Str::slug($role) }}">
                                        <label class="form-check-label" for="create-role-{{ Str::slug($role) }}">{{ $role }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Edit User') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="edit-user-form">
                    <input type="hidden" id="edit-user-id" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Name') }}</label>
                            <input id="edit-name" name="name" class="form-control" />
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Email') }}</label>
                            <input id="edit-email" name="email" type="email" class="form-control" />
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Password (leave blank to keep)') }}</label>
                            <input id="edit-password" name="password" type="password" class="form-control" />
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Roles</label>
                            <div id="edit-roles-container">
                                @foreach ($availableRoles as $role)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role }}" id="edit-role-{{ Str::slug($role) }}">
                                        <label class="form-check-label" for="edit-role-{{ Str::slug($role) }}">{{ $role }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            const createModal = new bootstrap.Modal('#createModal');
            const editModal = new bootstrap.Modal('#editModal');

            function showAlert(message, type = 'success') {
                const alertHtml = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;
                $('#alert-container').html(alertHtml);
                setTimeout(() => $('.alert').alert('close'), 5000);
            }

            $('#btn-new-user').click(function() {
                $('#create-user-form')[0].reset();
                $('.invalid-feedback').text('').hide();
                $('.form-control').removeClass('is-invalid');
                createModal.show();
            });

            $(document).on('click', '.btn-edit-user', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const email = $(this).data('email');
                const roles = $(this).data('roles');
                
                $('#edit-user-id').val(id);
                $('#edit-name').val(name);
                $('#edit-email').val(email);
                $('#edit-password').val('');
                
                // Uncheck all checkboxes first
                $('#edit-roles-container input[type="checkbox"]').prop('checked', false);
                
                // Check the roles this user has
                if (Array.isArray(roles)) {
                    roles.forEach(role => {
                        $(`#edit-role-${role.toLowerCase().replace(/\s+/g, '-')}`).prop('checked', true);
                    });
                }
                
                $('.invalid-feedback').text('').hide();
                $('.form-control').removeClass('is-invalid');
                editModal.show();
            });

            $('#create-user-form').submit(function(e) {
                e.preventDefault();
                
                const formData = $(this).serializeArray().reduce((obj, item) => {
                    if (item.name === 'roles[]') {
                        if (!obj.roles) obj.roles = [];
                        obj.roles.push(item.value);
                    } else {
                        obj[item.name] = item.value;
                    }
                    return obj;
                }, {});

                $.ajax({
                    url: '{{ route('admin.users.store') }}',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        createModal.hide();
                        showAlert(response.message, 'success');
                        location.reload();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            for (let field in errors) {
                                const input = $(`#create-user-form [name="${field}"]`);
                                input.addClass('is-invalid');
                                input.siblings('.invalid-feedback').text(errors[field][0]).show();
                            }
                        } else {
                            showAlert('An error occurred', 'danger');
                        }
                    }
                });
            });

            $('#edit-user-form').submit(function(e) {
                e.preventDefault();
                
                const id = $('#edit-user-id').val();
                const formData = $(this).serializeArray().reduce((obj, item) => {
                    if (item.name === 'roles[]') {
                        if (!obj.roles) obj.roles = [];
                        obj.roles.push(item.value);
                    } else if (item.name !== 'id') {
                        obj[item.name] = item.value;
                    }
                    return obj;
                }, {});

                $.ajax({
                    url: '{{ route('admin.users.update', ':id') }}'.replace(':id', id),
                    method: 'PUT',
                    data: formData,
                    success: function(response) {
                        editModal.hide();
                        showAlert(response.message, 'success');
                        location.reload();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            for (let field in errors) {
                                const input = $(`#edit-user-form [name="${field}"]`);
                                input.addClass('is-invalid');
                                input.siblings('.invalid-feedback').text(errors[field][0]).show();
                            }
                        } else {
                            showAlert('An error occurred', 'danger');
                        }
                    }
                });
            });

            $(document).on('click', '.btn-delete-user', function() {
                if (!confirm('{{ __('Are you sure you want to delete this user?') }}')) {
                    return;
                }

                const id = $(this).data('id');

                $.ajax({
                    url: '{{ route('admin.users.destroy', ':id') }}'.replace(':id', id),
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

            // Search functionality
            let searchTimeout;
            $('#search-input').on('input', function() {
                clearTimeout(searchTimeout);
                const query = $(this).val();
                
                searchTimeout = setTimeout(function() {
                    if (query.length > 0 || query === '') {
                        location.href = '{{ route('admin.users.index') }}?search=' + encodeURIComponent(query);
                    }
                }, 500);
            });

            $('.form-control').on('input change', function() {
                $(this).removeClass('is-invalid');
                $(this).siblings('.invalid-feedback').text('').hide();
            });
        });
    </script>
    @endpush
</x-layouts.app>
