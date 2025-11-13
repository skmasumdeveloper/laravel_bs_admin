<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Index extends Component
{
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

    /** @var array<int, string> */
    public array $availableRoles = [];

    public function mount(): void
    {
        $this->availableRoles = Role::orderBy('name')->pluck('name')->toArray();
    }

    public function getUsersProperty()
    {
        $query = User::with('roles')->orderBy('name');

        if ($this->search !== '') {
            $query->where(function ($q): void {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            });
        }

        return $query->get();
    }

    protected function rules(): array
    {
        $id = $this->form['id'] ?? null;

        return [
            'form.name' => ['required', 'string', 'max:255'],
            'form.email' => ['required', 'email', 'max:255', 'unique:users,email' . ($id ? ",{$id}" : '')],
            'form.password' => [$id ? 'nullable' : 'required', 'string', 'min:6'],
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
            Session::flash('error', __('User not found.'));

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
            'password' => Hash::make($this->form['password']),
        ]);

        if (! empty($this->form['roles'])) {
            $user->syncRoles($this->form['roles']);
        }

        Session::flash('success', __('User created.'));
        $this->resetForm();
        $this->showCreate = false;
        $this->dispatch('$refresh');
    }

    public function updateUser(): void
    {
        $this->validate();

        $user = User::find($this->form['id'] ?? null);

        if (! $user) {
            Session::flash('error', __('User not found.'));

            return;
        }

        $user->name = $this->form['name'];
        $user->email = $this->form['email'];

        if ($this->form['password']) {
            $user->password = Hash::make($this->form['password']);
        }

        $user->save();
        $user->syncRoles($this->form['roles'] ?? []);

        Session::flash('success', __('User updated.'));
        $this->resetForm();
        $this->showEdit = false;
        $this->dispatch('$refresh');
    }

    public function deleteUser(int $id): void
    {
        $user = User::find($id);

        if (! $user) {
            Session::flash('error', __('User not found.'));

            return;
        }

        if (Auth::id() === $user->id) {
            Session::flash('error', __('You cannot delete your own account.'));

            return;
        }

        $user->delete();

        Session::flash('success', __('User deleted.'));
        $this->dispatch('$refresh');
    }

    private function resetForm(): void
    {
        $this->form = [
            'id' => null,
            'name' => '',
            'email' => '',
            'password' => '',
            'roles' => [],
        ];
    }

    public function render()
    {
        return view('livewire.users.index');
    }
}
