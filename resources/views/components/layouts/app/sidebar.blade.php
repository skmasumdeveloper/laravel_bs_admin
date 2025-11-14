<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" >
    <head>
        @include('partials.head')
    </head>
    <body class="bg-light">
        <!-- Top navbar for mobile -->
        <nav class="navbar navbar-light bg-white border-bottom d-lg-none">
            <div class="container-fluid">
                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
                    ☰
                </button>

                <a class="navbar-brand ms-2" href="{{ route('admin.dashboard') }}">{{ config('app.name') }}</a>

                <div class="dropdown ms-auto">
                    <a class="btn btn-light dropdown-toggle" href="#" role="button" id="mobileUserMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ auth()->user()->initials() ?? '' }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="mobileUserMenu">
                        <li class="px-3 py-2">
                            <div class="small">{{ auth()->user()->name ?? '' }}</div>
                            <div class="text-muted small">{{ auth()->user()->email ?? '' }}</div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('admin.settings.profile') }}">{{ __('Settings') }}</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">@csrf
                                <button class="dropdown-item" type="submit">{{ __('Log Out') }}</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar (offcanvas on mobile) -->
                <div class="col-lg-3 col-xl-2 d-none d-lg-block bg-white border-end vh-100 position-fixed">
                    <div class="p-3 d-flex flex-column h-100 overflow-auto">
                        <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center mb-3 text-decoration-none">
                            <x-app-logo />
                        </a>

                        <nav class="nav flex-column">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <span class="material-icons align-middle me-2" style="font-size: 20px; vertical-align: -5px;">dashboard</span>{{ __('Dashboard') }}
                            </a>

                            @role('admin')
                                <div class="mt-3">
                                    <div class="text-uppercase small text-muted mb-2">{{ __('Administration') }}</div>
                                    <a class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                        <span class="material-icons align-middle me-2" style="font-size: 20px; vertical-align: -5px;">people</span>{{ __('Users') }}
                                    </a>
                                    <a class="nav-link {{ request()->routeIs('admin.roles.index') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
                                        <span class="material-icons align-middle me-2" style="font-size: 20px; vertical-align: -5px;">badge</span>{{ __('Roles') }}
                                    </a>
                                    <a class="nav-link {{ request()->routeIs('admin.users.roles') ? 'active' : '' }}" href="{{ route('admin.users.roles') }}">
                                        <span class="material-icons align-middle me-2" style="font-size: 20px; vertical-align: -5px;">person_add</span>{{ __('Assign Roles') }}
                                    </a>
                                    <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                                        <span class="material-icons align-middle me-2" style="font-size: 20px; vertical-align: -5px;">category</span>{{ __('Categories') }}
                                    </a>
                                    <a class="nav-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}" href="{{ route('admin.blogs.index') }}">
                                        <span class="material-icons align-middle me-2" style="font-size: 20px; vertical-align: -5px;">article</span>{{ __('Blog Posts') }}
                                    </a>
                                    <a class="nav-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}" href="{{ route('admin.pages.index') }}">
                                        <span class="material-icons align-middle me-2" style="font-size: 20px; vertical-align: -5px;">description</span>{{ __('Pages') }}
                                    </a>
                                </div>
                            @endrole

                            <div class="mt-4">
                                {{-- <a class="nav-link" href="https://github.com/laravel/livewire-starter-kit" target="_blank">{{ __('Repository') }}</a>
                                <a class="nav-link" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">{{ __('Documentation') }}</a> --}}
                            </div>
                        </nav>

                        <div class="mt-auto pt-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width:40px;height:40px">{{ auth()->user()->initials() ?? '' }}</div>
                                <div>
                                    <div class="fw-semibold">{{ auth()->user()->name ?? '' }}</div>
                                    <div class="small text-muted">{{ auth()->user()->email ?? '' }}</div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <a href="{{ route('admin.settings.profile') }}" class="btn btn-sm btn-outline-secondary w-100 mb-2">{{ __('Settings') }}</a>
                                <form method="POST" action="{{ route('logout') }}">@csrf
                                    <button type="submit" class="btn btn-sm btn-danger w-100">{{ __('Log Out') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Offcanvas sidebar for mobile -->
                <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="sidebarOffcanvasLabel">{{ config('app.name') }}</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <nav class="nav flex-column">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <span class="material-icons align-middle me-2" style="font-size: 20px; vertical-align: -5px;">dashboard</span>{{ __('Dashboard') }}
                            </a>
                            @role('admin')
                                <div class="mt-3">
                                    <div class="text-uppercase small text-muted mb-2">{{ __('Administration') }}</div>
                                    <a class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                        <span class="material-icons align-middle me-2" style="font-size: 20px; vertical-align: -5px;">people</span>{{ __('Users') }}
                                    </a>
                                    <a class="nav-link {{ request()->routeIs('admin.roles.index') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
                                        <span class="material-icons align-middle me-2" style="font-size: 20px; vertical-align: -5px;">badge</span>{{ __('Roles') }}
                                    </a>
                                    <a class="nav-link {{ request()->routeIs('admin.users.roles') ? 'active' : '' }}" href="{{ route('admin.users.roles') }}">
                                        <span class="material-icons align-middle me-2" style="font-size: 20px; vertical-align: -5px;">person_add</span>{{ __('Assign Roles') }}
                                    </a>
                                    <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                                        <span class="material-icons align-middle me-2" style="font-size: 20px; vertical-align: -5px;">category</span>{{ __('Categories') }}
                                    </a>
                                    <a class="nav-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}" href="{{ route('admin.blogs.index') }}">
                                        <span class="material-icons align-middle me-2" style="font-size: 20px; vertical-align: -5px;">article</span>{{ __('Blog Posts') }}
                                    </a>
                                    <a class="nav-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}" href="{{ route('admin.pages.index') }}">
                                        <span class="material-icons align-middle me-2" style="font-size: 20px; vertical-align: -5px;">description</span>{{ __('Pages') }}
                                    </a>
                                </div>
                            @endrole
                            <div class="mt-4">
                                {{-- <a class="nav-link" href="https://github.com/laravel/livewire-starter-kit" target="_blank">{{ __('Repository') }}</a>
                                <a class="nav-link" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">{{ __('Documentation') }}</a> --}}
                            </div>
                        </nav>
                    </div>
                </div>

                <!-- Main content area -->
                <div class="col-lg-9 offset-lg-3 col-xl-10 offset-xl-2">
                    <div class="p-4">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
