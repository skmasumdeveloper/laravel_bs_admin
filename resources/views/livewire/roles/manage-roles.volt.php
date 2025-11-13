<?php

use Livewire\Volt\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

new class extends Component {
    public string $name = '';
    public array $permissions = [];
    public array $availablePermissions = [];

    public function mount(): void
    {
        $this->availablePermissions = Permission::orderBy('name')->pluck('name')->toArray();
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => ['array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ];
    }

    public function createRole(): void
    {
        $this->validate();

        $role = Role::create(['name' => $this->name, 'guard_name' => 'web']);
        if (! empty($this->permissions)) {
            $role->syncPermissions($this->permissions);
        }

        $this->reset(['name', 'permissions']);
        session()->flash('success', 'Role created successfully.');

        // Refresh available roles list
        $this->dispatch('$refresh');
    }

    public function deleteRole(int $roleId): void
    {
        $role = Role::find($roleId);
        if (! $role) {
            session()->flash('error', 'Role not found.');
            return;
        }

        if ($role->name === 'admin') {
            session()->flash('error', 'Cannot delete the admin role.');
            return;
        }

        $role->delete();
        session()->flash('success', 'Role deleted.');
        $this->dispatch('$refresh');
    }

    public function getRolesProperty()
    {
        return Role::with('permissions')->orderBy('name')->get();
    }
};
?>

<div class="space-y-6">
    <h1 class="text-xl font-semibold">Manage Roles</h1>

    @if (session('success'))
        <div class="rounded-md bg-green-50 p-3 text-green-700">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="rounded-md bg-red-50 p-3 text-red-700">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="rounded-lg border bg-white p-4">
            <h2 class="mb-4 font-medium">Create New Role</h2>
            <form wire:submit.prevent="createRole" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Role Name</label>
                    <input type="text" wire:model.defer="name" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g. editor" />
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Permissions</label>
                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-48 overflow-auto">
                        @forelse ($availablePermissions as $perm)
                            <label class="inline-flex items-center space-x-2">
                                <input type="checkbox" wire:model="permissions" value="{{ $perm }}" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm text-gray-700">{{ $perm }}</span>
                            </label>
                        @empty
                            <p class="text-sm text-gray-500">No permissions defined yet.</p>
                        @endforelse
                    </div>
                    @error('permissions.*') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">Create Role</button>
                </div>
            </form>
        </div>

        <div class="rounded-lg border bg-white p-4">
            <h2 class="mb-4 font-medium">Existing Roles</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Role</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Permissions</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($this->roles as $role)
                            <tr>
                                <td class="px-4 py-2 font-medium text-gray-900">{{ $role->name }}</td>
                                <td class="px-4 py-2">
                                    @if ($role->permissions->isEmpty())
                                        <span class="text-gray-500">—</span>
                                    @else
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($role->permissions as $perm)
                                                <span class="inline-flex items-center rounded bg-gray-100 px-2 py-0.5 text-xs text-gray-800">{{ $perm->name }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-right">
                                    <button wire:click="deleteRole({{ $role->id }})" class="text-red-600 hover:underline" @disabled($role->name === 'admin')>Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-gray-500">No roles found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
