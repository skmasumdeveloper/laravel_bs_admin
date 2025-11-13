<x-layouts.auth>
    <div class="card mx-auto" style="max-width:540px;">
        <div class="card-body">
            <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

            <x-auth-session-status class="text-center" :status="session('status')" />

            <form method="POST" action="{{ route('register.store') }}" class="w-100 mt-3">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">{{ __('Name') }}</label>
                    <input id="name" name="name" type="text" class="form-control" required autofocus value="{{ old('name') }}" placeholder="{{ __('Full name') }}">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <input id="email" name="email" type="email" class="form-control" required value="{{ old('email') }}" placeholder="email@example.com">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <input id="password" name="password" type="password" class="form-control" required autocomplete="new-password">
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required autocomplete="new-password">
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">{{ __('Register') }}</button>
                </div>
            </form>

            <div class="text-center small text-muted mt-3">
                <span>{{ __('Already have an account?') }}</span>
                <a href="{{ route('login') }}">{{ __('Log in') }}</a>
            </div>
        </div>
    </div>
</x-layouts.auth>
