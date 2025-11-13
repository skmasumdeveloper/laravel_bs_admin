<x-layouts.auth>
    <div class="card mx-auto" style="max-width:540px;">
        <div class="card-body">
            <x-auth-header :title="__('Log in to your account')" :description="__('Enter your email and password below to log in')" />

            <x-auth-session-status class="text-center" :status="session('status')" />

            <form method="POST" action="{{ route('login.store') }}" class="w-100 mt-3">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Email address') }}</label>
                    <input id="email" name="email" type="email" class="form-control" required autofocus autocomplete="email" placeholder="email@example.com" value="{{ old('email') }}">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <input id="password" name="password" type="password" class="form-control" required autocomplete="current-password">
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">{{ __('Remember me') }}</label>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
                    @endif

                    <button type="submit" class="btn btn-primary" data-test="login-button">{{ __('Log in') }}</button>
                </div>
            </form>

            @if (Route::has('register'))
                <div class="text-center small text-muted mt-3">
                    {{ __('Don\'t have an account?') }} <a href="{{ route('register') }}">{{ __('Sign up') }}</a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.auth>
