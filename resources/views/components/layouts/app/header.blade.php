<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-vh-100 bg-white">
        <header class="border-bottom bg-light">
            <nav class="navbar navbar-expand-lg container-fluid">
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-secondary me-2 d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <a href="{{ route('dashboard') }}" class="navbar-brand d-flex align-items-center" wire:navigate>
                        <x-app-logo />
                    </a>
                </div>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNavbar">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}" wire:navigate>{{ __('Dashboard') }}</a>
                        </li>
                    </ul>

                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item d-none d-lg-block me-2">
                            <a class="nav-link" href="#" title="{{ __('Search') }}">🔍</a>
                        </li>
                        <li class="nav-item d-none d-lg-block me-2">
                            <a class="nav-link" href="https://github.com/laravel/livewire-starter-kit" target="_blank" title="{{ __('Repository') }}">📁</a>
                        </li>
                        <li class="nav-item d-none d-lg-block me-3">
                            <a class="nav-link" href="https://laravel.com/docs/starter-kits#livewire" target="_blank" title="{{ __('Documentation') }}">📚</a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="badge bg-secondary rounded-circle me-2">{{ auth()->user()->initials() }}</span>
                                <span class="d-none d-lg-inline">{{ auth()->user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li class="px-3 py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <span class="badge bg-light text-dark rounded-circle">{{ auth()->user()->initials() }}</span>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ auth()->user()->name }}</div>
                                            <div class="small text-muted">{{ auth()->user()->email }}</div>
                                        </div>
                                    </div>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}" wire:navigate>{{ __('Settings') }}</a></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item" data-test="logout-button">{{ __('Log Out') }}</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <!-- Sidebar offcanvas for mobile -->
        <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="sidebarOffcanvasLabel">{{ config('app.name') }}</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}" wire:navigate>{{ __('Dashboard') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="https://github.com/laravel/livewire-starter-kit" target="_blank">{{ __('Repository') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">{{ __('Documentation') }}</a></li>
                </ul>
            </div>
        </div>

        <!-- additional mobile navigation handled by the offcanvas sidebar above -->

        {{ $slot }}

        <!-- Flux scripts removed; Bootstrap JS loaded via CDN in head -->
    </body>
</html>
