<x-layouts.app :title="__('Assign Roles')">
    <div class="container py-4">
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h1 class="h5 mb-0">Assign Roles</h1>
                    <p class="small text-muted mb-0">Assign roles to users.</p>
                </div>
            </div>

            <div id="alert-container"></div>

            <div class="row">
                <div class="col-lg-5 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">{{ __('Assign Roles to User') }}</h6>

                            <form id="assign-form">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('User') }}</label>
                                    <select id="user-select" name="user_id" class="form-select">
                                        <option value="">— {{ __('Select User') }} —</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Roles') }}</label>
                                    <div id="roles-container">
                                        @foreach ($roles as $role)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->name }}" id="role-{{ Str::slug($role->name) }}">
                                                <label class="form-check-label" for="role-{{ Str::slug($role->name) }}">{{ $role->name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">{{ __('Assign Roles') }}</button>
                                    <button type="button" id="btn-reset-assign" class="btn btn-outline-secondary">{{ __('Reset') }}</button>
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
                                            <th>{{ __('User') }}</th>
                                            <th>{{ __('Email') }}</th>
                                            <th>{{ __('Roles') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td class="fw-semibold">{{ $user->name }}</td>
                                                <td class="text-muted">{{ $user->email }}</td>
                                                <td>
                                                    @if($user->roles->isNotEmpty())
                                                        @foreach($user->roles as $role)
                                                            <span class="badge bg-secondary me-1">{{ $role->name }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted small">{{ __('No roles') }}</span>
                                                    @endif
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
            const usersData = @json($users);

            function showAlert(message, type = 'success') {
                const alertHtml = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;
                $('#alert-container').html(alertHtml);
                setTimeout(() => $('.alert').alert('close'), 5000);
            }

            function resetForm() {
                $('#assign-form')[0].reset();
                $('.invalid-feedback').text('').hide();
                $('.form-control, .form-select').removeClass('is-invalid');
            }

            // When user is selected, check their current roles
            $('#user-select').change(function() {
                const userId = $(this).val();
                
                // Uncheck all roles first
                $('#roles-container input[type="checkbox"]').prop('checked', false);
                
                if (userId) {
                    const user = usersData.find(u => u.id == userId);
                    if (user && user.roles) {
                        user.roles.forEach(role => {
                            $(`#role-${role.name.toLowerCase().replace(/\s+/g, '-')}`).prop('checked', true);
                        });
                    }
                }
            });

            $('#btn-reset-assign').click(function() {
                resetForm();
            });

            $('#assign-form').submit(function(e) {
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
                    url: '{{ route('admin.users.roles.assign') }}',
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
                                const fieldName = field.replace('_', '-');
                                const input = $(`#${fieldName}-select, [name="${field}"]`).first();
                                input.addClass('is-invalid');
                                input.siblings('.invalid-feedback').text(errors[field][0]).show();
                            }
                        } else {
                            showAlert('An error occurred', 'danger');
                        }
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
