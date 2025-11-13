@php
    $title = View::getSection('title') ?? ($title ?? null);
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="bg-light text-dark d-flex flex-column min-vh-100">
        <header class="border-bottom bg-white">
            <div class="container d-flex justify-content-between align-items-center py-3">
                <a href="{{ route('home') }}" class="navbar-brand fw-bold">
                    {{ config('app.name') }}
                </a>

                <nav class="d-flex gap-3 align-items-center">
                    <a href="{{ route('home') }}" class="text-decoration-none text-muted">{{ __('Home') }}</a>
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-primary">{{ __('Admin') }}</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">{{ __('Login') }}</a>
                    @endauth
                </nav>
            </div>
        </header>

        <main class="flex-fill">
            @yield('content')
        </main>

        <footer class="bg-white border-top py-4 mt-auto">
            <div class="container text-center text-muted small">
                &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved.') }}
            </div>
        </footer>
    </body>
</html>
