<div>
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
</div>
