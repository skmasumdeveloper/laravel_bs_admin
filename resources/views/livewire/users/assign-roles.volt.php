<?php

use App\Models\User;
use Livewire\Volt\Component;
use Spatie\Permission\Models\Role;

new class extends Component {
    public array $userRoles = [];
    public array $availableRoles = [];

    public function mount(): void
    {
        $this->availableRoles = Role::orderBy('name')->pluck('name')->toArray();

        // Initialize user roles mapping
        foreach (User::with('roles:id,name')->get(['id']) as $u) {
            $this->userRoles[$u->id] = $u->roles->pluck('name')->toArray();
        }
    }

    public function updateUserRoles(int $userId): void
    {
        $user = User::find($userId);
        if (! $user) {
            session()->flash('error', 'User not found.');
            return;
        }

        $roles = $this->userRoles[$userId] ?? [];
        $user->syncRoles($roles);

        session()->flash('success', 'User roles updated.');
        $this->dispatch('$refresh');
    }

    public function getUsersProperty()
    {
        return User::with('roles:id,name')->orderBy('name')->get();
    }
};
?>

<div class="space-y-6">
    <h1 class="text-xl font-semibold">Assign Roles to Users</h1>

    @if (session('success'))
        <div class="rounded-md bg-green-50 p-3 text-green-700">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="rounded-md bg-red-50 p-3 text-red-700">{{ session('error') }}</div>
    @endif

    <div class="rounded-lg border bg-white p-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left font-medium text-gray-700">User</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-700">Roles</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($this->users as $user)
                    <tr>
                        <td class="px-4 py-2">
                            <div class="font-medium text-gray-900">{{ $user->name }}</div>
                            <div class="text-gray-500 text-xs">{{ $user->email }}</div>
                        </td>
                        <td class="px-4 py-2">
                            <div class="flex flex-wrap gap-3">
                                @foreach ($availableRoles as $role)
                                    <label class="inline-flex items-center space-x-2">
                                        <input type="checkbox" wire:model="userRoles.{{ $user->id }}" value="{{ $role }}" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-sm text-gray-700">{{ $role }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-2 text-right">
                            <button wire:click="updateUserRoles({{ $user->id }})" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-1.5 text-white hover:bg-indigo-700">Save</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-center text-gray-500">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
