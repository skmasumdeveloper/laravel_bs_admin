<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component {
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => $validated['password'],
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Update password')" :subheading="__('Ensure your account is using a long, random password to stay secure')">
        <form method="POST" wire:submit="updatePassword" class="mt-4">
            <div class="mb-3">
                <label class="form-label">{{ __('Current password') }}</label>
                <input wire:model="current_password" type="password" class="form-control" required autocomplete="current-password" />
                @error('current_password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('New password') }}</label>
                <input wire:model="password" type="password" class="form-control" required autocomplete="new-password" />
                @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('Confirm Password') }}</label>
                <input wire:model="password_confirmation" type="password" class="form-control" required autocomplete="new-password" />
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="flex-grow-1">
                    <button type="submit" class="btn btn-primary w-100" data-test="update-password-button">{{ __('Save') }}</button>
                </div>

                <x-action-message class="ms-3" on="password-updated">{{ __('Saved.') }}</x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>
