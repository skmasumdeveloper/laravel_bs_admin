<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="mt-4">
    <div class="mb-3">
        <h3 class="h5">{{ __('Delete account') }}</h3>
        <p class="text-muted">{{ __('Delete your account and all of its resources') }}</p>
    </div>

    <button type="button" class="btn btn-danger" onclick="document.getElementById('confirm-user-deletion').classList.remove('d-none')" data-test="delete-user-button">
        {{ __('Delete account') }}
    </button>

    <div id="confirm-user-deletion" class="modal fade {{ $errors->isNotEmpty() ? 'show d-block' : 'd-none' }}" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" wire:submit="deleteUser">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Are you sure you want to delete your account?') }}</h5>
                        <button type="button" class="btn-close" aria-label="Close" onclick="document.getElementById('confirm-user-deletion').classList.add('d-none')"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}</p>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Password') }}</label>
                            <input wire:model="password" type="password" class="form-control" />
                            @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="document.getElementById('confirm-user-deletion').classList.add('d-none')">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-danger" data-test="confirm-delete-user-button">{{ __('Delete account') }}</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    </div>
</section>
