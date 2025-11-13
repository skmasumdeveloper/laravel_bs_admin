<div class="row">
    <div class="col-md-3 mb-3">
        <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
            <a class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">{{ __('Profile') }}</a>
            <a class="nav-link {{ request()->routeIs('user-password.edit') ? 'active' : '' }}" href="{{ route('user-password.edit') }}">{{ __('Password') }}</a>

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <a class="nav-link {{ request()->routeIs('two-factor.show') ? 'active' : '' }}" href="{{ route('two-factor.show') }}">{{ __('Two-Factor Auth') }}</a>
            @endif

            <a class="nav-link {{ request()->routeIs('appearance.edit') ? 'active' : '' }}" href="{{ route('appearance.edit') }}">{{ __('Appearance') }}</a>
        </div>
    </div>

    <div class="col-md-9">
        <h2 class="h4">{{ $heading ?? '' }}</h2>
        <p class="text-muted">{{ $subheading ?? '' }}</p>

        <div class="mt-4 w-100">
            {{ $slot }}
        </div>
    </div>
</div>
