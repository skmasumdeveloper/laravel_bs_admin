<x-layouts.auth>
    <div class="mt-4 d-flex flex-column gap-3 text-center">
        <p class="mb-3">{{ __('Please verify your email address by clicking on the link we just emailed to you.') }}</p>

        @if (session('status') == 'verification-link-sent')
            <p class="text-success mb-3">{{ __('A new verification link has been sent to the email address you provided during registration.') }}</p>
        @endif

        <div class="d-flex flex-column align-items-center gap-2">
            <form method="POST" action="{{ route('verification.send') }}" class="w-100 mb-2">@csrf
                <button type="submit" class="btn btn-primary w-100">{{ __('Resend verification email') }}</button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="w-100">@csrf
                <button type="submit" class="btn btn-link text-muted small">{{ __('Log out') }}</button>
            </form>
        </div>
    </div>
</x-layouts.auth>
