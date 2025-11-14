<div>
    <form wire:submit="updateProfileInformation" class="my-4 w-full">
        <div class="mb-3">
            <label class="form-label">{{ __('Name') }}</label>
            <input wire:model="name" type="text" class="form-control" required autofocus autocomplete="name" />
            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">{{ __('Email') }}</label>
            <input wire:model="email" type="email" class="form-control" required autocomplete="email" />
            @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div class="mt-3">
                    <p class="mb-1">{{ __('Your email address is unverified.') }}</p>
                    <button type="button" class="btn btn-link p-0" wire:click.prevent="resendVerificationNotification">{{ __('Click here to re-send the verification email.') }}</button>

                    @if (session('status') === 'verification-link-sent')
                        <div class="mt-2 small text-success">{{ __('A new verification link has been sent to your email address.') }}</div>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            <div class="flex-grow-1">
                <button type="submit" class="btn btn-primary w-100" data-test="update-profile-button">{{ __('Save') }}</button>
            </div>

            <x-action-message class="ms-3" on="profile-updated">{{ __('Saved.') }}</x-action-message>
        </div>
    </form>
</div>
