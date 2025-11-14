<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class AssignRoles extends Component
{
    public array $userRoles = [];
    public array $availableRoles = [];

    public function mount(): void
    {
        $this->availableRoles = Role::orderBy('name')->pluck('name')->toArray();

        foreach (User::with('roles:id,name')->get(['id']) as $user) {
            $this->userRoles[$user->id] = $user->roles->pluck('name')->toArray();
        }
    }

    public function updateUserRoles(int $userId): void
    {
        $user = User::find($userId);

        if (! $user) {
            Session::flash('error', __('User not found.'));

            return;
        }

        $roles = $this->userRoles[$userId] ?? [];
        $user->syncRoles($roles);

        Session::flash('success', __('User roles updated.'));
        $this->dispatch('$refresh');
    }

    public function getUsersProperty()
    {
        return User::with('roles:id,name')->orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.users.assign-roles');
    }
}
