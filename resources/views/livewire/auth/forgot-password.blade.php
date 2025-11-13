<x-layouts.auth>
    <div class="d-flex flex-column gap-3">
        <x-auth-header :title="__('Forgot password')" :description="__('Enter your email to receive a password reset link')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="w-100">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                <input id="email" name="email" type="email" class="form-control" required autofocus placeholder="email@example.com" value="{{ old('email') }}">
            </div>

            <button type="submit" class="btn btn-primary w-100" data-test="email-password-reset-link-button">{{ __('Email password reset link') }}</button>
        </form>

        <div class="text-center small text-muted mt-3">
            <span>{{ __('Or, return to') }}</span>
            <a href="{{ route('login') }}">{{ __('log in') }}</a>
        </div>
    </div>
</x-layouts.auth>
