<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h5 mb-0">Manage Roles</h1>
        <div>
            <!-- search omitted (not bound) -->
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-title">Create New Role</h6>
                    <form wire:submit.prevent="createRole">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Role Name') }}</label>
                            <input wire:model.defer="name" class="form-control" placeholder="e.g. editor" />
                            @error('name') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Permissions</label>
                            <div class="d-flex flex-wrap gap-2 overflow-auto" style="max-height:180px">
                                @forelse ($availablePermissions as $perm)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" wire:model="permissions" value="{{ $perm }}" id="perm-{{ \Illuminate\Support\Str::slug($perm) }}">
                                        <label class="form-check-label small" for="perm-{{ \Illuminate\Support\Str::slug($perm) }}">{{ $perm }}</label>
                                    </div>
                                @empty
                                    <div class="small text-muted">No permissions defined yet.</div>
                                @endforelse
                            </div>
                            @error('permissions.*') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary">{{ __('Create Role') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8 mb-3">
            <div class="card">
                <div class="card-body p-0">
                    <h6 class="card-title px-3 pt-3">Existing Roles</h6>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Role</th>
                                    <th>Permissions</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($this->roles as $role)
                                    <tr>
                                        <td class="fw-semibold">{{ $role->name }}</td>
                                        <td>
                                            @if ($role->permissions->isEmpty())
                                                <span class="text-muted">—</span>
                                            @else
                                                @foreach ($role->permissions as $perm)
                                                    <span class="badge bg-secondary me-1">{{ $perm->name }}</span>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <button wire:click="deleteRole({{ $role->id }})" class="btn btn-sm btn-link text-danger" @disabled($role->name === 'admin')>Delete</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No roles found.</td>
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
