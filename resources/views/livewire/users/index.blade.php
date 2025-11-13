<?php

use App\Models\User;
use Livewire\Volt\Component;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

new class extends Component {
    public string $search = '';
    public array $form = [
        'id' => null,
        'name' => '',
        'email' => '',
        'password' => '',
        'roles' => [],
    ];

    public bool $showCreate = false;
    public bool $showEdit = false;
    public array $availableRoles = [];

    public function mount(): void
    {
        $this->availableRoles = Role::orderBy('name')->pluck('name')->toArray();
    }

    public function getUsersProperty()
    {
        $q = User::with('roles')->orderBy('name');

        if ($this->search) {
            $q->where(fn ($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('email', 'like', "%{$this->search}%"));
        }

        return $q->get();
    }

    protected function rules(): array
    {
        return [
            'form.name' => ['required', 'string', 'max:255'],
            'form.email' => ['required', 'email', 'max:255', 'unique:users,email' . ($this->form['id'] ? ",{$this->form['id']}" : '')],
            'form.password' => [$this->form['id'] ? 'nullable' : 'required', 'string', 'min:6'],
            'form.roles' => ['array'],
        ];
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showCreate = true;
    }

    public function openEdit(int $id): void
    {
        $user = User::with('roles')->find($id);
        if (! $user) {
            session()->flash('error', 'User not found.');
            return;
        }

        $this->form = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'password' => '',
            'roles' => $user->roles->pluck('name')->toArray(),
        ];

        $this->showEdit = true;
    }

    public function createUser(): void
    {
        $this->validate();

        $user = User::create([
            'name' => $this->form['name'],
            'email' => $this->form['email'],
            'password' => bcrypt($this->form['password']),
        ]);

        if (! empty($this->form['roles'])) {
            $user->syncRoles($this->form['roles']);
        }

        session()->flash('success', 'User created.');
        $this->resetForm();
        $this->showCreate = false;
        $this->dispatch('$refresh');
    }

    public function updateUser(): void
    {
        $this->validate();

        $user = User::find($this->form['id']);
        if (! $user) {
            session()->flash('error', 'User not found.');
            return;
        }

        $user->name = $this->form['name'];
        $user->email = $this->form['email'];
        if (! empty($this->form['password'])) {
            $user->password = bcrypt($this->form['password']);
        }
        $user->save();

        $user->syncRoles($this->form['roles'] ?? []);

        session()->flash('success', 'User updated.');
        $this->resetForm();
        $this->showEdit = false;
        $this->dispatch('$refresh');
    }

    public function deleteUser(int $id): void
    {
        $user = User::find($id);
        if (! $user) {
            session()->flash('error', 'User not found.');
            return;
        }

        // Prevent deleting yourself accidentally
        if (auth()->id() === $user->id) {
            session()->flash('error', 'You cannot delete your own account.');
            return;
        }

        $user->delete();
        session()->flash('success', 'User deleted.');
        $this->dispatch('$refresh');
    }

    private function resetForm(): void
    {
        $this->form = ['id' => null, 'name' => '', 'email' => '', 'password' => '', 'roles' => []];
    }
}; ?>

<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h5 mb-0">Users</h1>
            <p class="small text-muted mb-0">Create, edit and manage users and their roles.</p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <input wire:model.debounce.300ms="search" type="search" class="form-control form-control-sm me-2" placeholder="Search users..." style="width:220px">
            <button wire:click="openCreate" class="btn btn-primary">{{ __('New User') }}</button>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

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
                    <tbody>
                        @forelse ($this->users as $user)
                            <tr>
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td class="text-muted">{{ $user->email }}</td>
                                <td>
                                    @foreach ($user->roles as $role)
                                        <span class="badge bg-secondary me-1">{{ $role->name }}</span>
                                    @endforeach
                                </td>
                                <td class="text-end">
                                    <button wire:click="openEdit({{ $user->id }})" class="btn btn-sm btn-link">Edit</button>
                                    <button wire:click="deleteUser({{ $user->id }})" class="btn btn-sm btn-link text-danger">Delete</button>
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

    <!-- Create Modal (rendered when $showCreate true) -->
    @if ($showCreate)
        <div class="modal d-block" tabindex="-1" role="dialog" style="background:rgba(0,0,0,0.4)">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Create User') }}</h5>
                        <button type="button" class="btn-close" aria-label="Close" wire:click="$set('showCreate', false)"></button>
                    </div>
                    <form wire:submit.prevent="createUser">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">{{ __('Name') }}</label>
                                <input wire:model.defer="form.name" class="form-control" />
                                @error('form.name') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('Email') }}</label>
                                <input wire:model.defer="form.email" class="form-control" />
                                @error('form.email') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('Password') }}</label>
                                <input wire:model.defer="form.password" type="password" class="form-control" />
                                @error('form.password') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Roles</label>
                                <div>
                                    @foreach ($availableRoles as $role)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" wire:model="form.roles" value="{{ $role }}" id="create-role-{{ Str::slug($role) }}">
                                            <label class="form-check-label" for="create-role-{{ Str::slug($role) }}">{{ $role }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="$set('showCreate', false)">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Modal -->
    @if ($showEdit)
        <div class="modal d-block" tabindex="-1" role="dialog" style="background:rgba(0,0,0,0.4)">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Edit User') }}</h5>
                        <button type="button" class="btn-close" aria-label="Close" wire:click="$set('showEdit', false)"></button>
                    </div>
                    <form wire:submit.prevent="updateUser">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">{{ __('Name') }}</label>
                                <input wire:model.defer="form.name" class="form-control" />
                                @error('form.name') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('Email') }}</label>
                                <input wire:model.defer="form.email" class="form-control" />
                                @error('form.email') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('Password (leave blank to keep)') }}</label>
                                <input wire:model.defer="form.password" type="password" class="form-control" />
                                @error('form.password') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Roles</label>
                                <div>
                                    @foreach ($availableRoles as $role)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" wire:model="form.roles" value="{{ $role }}" id="edit-role-{{ Str::slug($role) }}">
                                            <label class="form-check-label" for="edit-role-{{ Str::slug($role) }}">{{ $role }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="$set('showEdit', false)">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

</div>
