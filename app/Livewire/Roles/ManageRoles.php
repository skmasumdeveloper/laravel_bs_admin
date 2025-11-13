<?php

namespace App\Livewire\Roles;

use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ManageRoles extends Component
{
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

        if ($this->permissions !== []) {
            $role->syncPermissions($this->permissions);
        }

        $this->reset(['name', 'permissions']);

        Session::flash('success', __('Role created successfully.'));

        $this->dispatch('$refresh');
    }

    public function deleteRole(int $roleId): void
    {
        $role = Role::find($roleId);

        if (! $role) {
            Session::flash('error', __('Role not found.'));

            return;
        }

        if ($role->name === 'admin') {
            Session::flash('error', __('Cannot delete the admin role.'));

            return;
        }

        $role->delete();

        Session::flash('success', __('Role deleted.'));

        $this->dispatch('$refresh');
    }

    public function getRolesProperty()
    {
        return Role::with('permissions')->orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.roles.manage-roles');
    }
}
