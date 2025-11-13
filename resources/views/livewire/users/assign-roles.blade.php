<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h5 mb-0">Assign Roles to Users</h1>
        <div class="small text-muted">Select roles and click Save per user.</div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Roles</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($this->users as $user)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $user->name }}</div>
                                    <div class="small text-muted">{{ $user->email }}</div>
                                </td>
                                <td>
                                    <div>
                                        @foreach ($availableRoles as $role)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" wire:model="userRoles.{{ $user->id }}" value="{{ $role }}" id="user-{{ $user->id }}-role-{{ \Illuminate\Support\Str::slug($role) }}">
                                                <label class="form-check-label small" for="user-{{ $user->id }}-role-{{ \Illuminate\Support\Str::slug($role) }}">{{ $role }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="text-end">
                                    <button wire:click="updateUserRoles({{ $user->id }})" class="btn btn-sm btn-primary">{{ __('Save') }}</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
