<x-layouts.auth>
    <div class="d-flex flex-column gap-3">
        <x-auth-header
            :title="__('Confirm password')"
            :description="__('This is a secure area of the application. Please confirm your password before continuing.')"
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.confirm.store') }}" class="w-100">
            @csrf

            <div class="mb-3">
                <label for="password" class="form-label">{{ __('Password') }}</label>
                <input id="password" name="password" type="password" class="form-control" required autocomplete="current-password" placeholder="{{ __('Password') }}">
            </div>

            <button type="submit" class="btn btn-primary w-100" data-test="confirm-password-button">{{ __('Confirm') }}</button>
        </form>
    </div>
</x-layouts.auth>
