<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-vh-100 bg-light d-flex align-items-center">
        <div class="container d-flex align-items-center justify-content-center min-vh-100 py-4">
            <div class="w-100" style="max-width:420px;">
                <div class="text-center mb-3">
                    <a href="{{ route('home') }}" class="d-inline-flex align-items-center">
                        <span class="me-2">
                            <x-app-logo-icon class="" />
                        </span>
                        <span class="visually-hidden">{{ config('app.name', 'Laravel') }}</span>
                    </a>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">{{ $slot }}</div>
                </div>
            </div>
        </div>
    </body>
</html>
