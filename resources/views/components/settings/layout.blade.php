<div class="row">
    <div class="col-md-3 mb-3">
        <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
            <a class="nav-link {{ request()->routeIs('admin.settings.profile') ? 'active' : '' }}" href="{{ route('admin.settings.profile') }}">{{ __('Profile') }}</a>
            <a class="nav-link {{ request()->routeIs('admin.settings.password') ? 'active' : '' }}" href="{{ route('admin.settings.password') }}">{{ __('Password') }}</a>

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <a class="nav-link {{ request()->routeIs('admin.settings.two-factor') ? 'active' : '' }}" href="{{ route('admin.settings.two-factor') }}">{{ __('Two-Factor Auth') }}</a>
            @endif

            <a class="nav-link {{ request()->routeIs('admin.settings.appearance') ? 'active' : '' }}" href="{{ route('admin.settings.appearance') }}">{{ __('Appearance') }}</a>
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
