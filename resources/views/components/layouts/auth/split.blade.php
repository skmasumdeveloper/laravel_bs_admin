<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-vh-100 bg-light">
        <div class="container-fluid min-vh-100">
            <div class="row gx-0 align-items-stretch" style="min-height:100vh;">
                <div class="col-lg-6 d-none d-lg-flex bg-dark text-white flex-column p-4">
                    <div>
                        <a href="{{ route('home') }}" class="d-inline-flex align-items-center text-white text-decoration-none mb-3" wire:navigate>
                            <span class="me-2">
                                <x-app-logo-icon class="" />
                            </span>
                            {{ config('app.name', 'Laravel') }}
                        </a>
                    </div>

                    @php
                        [$message, $author] = str(Illuminate\Foundation\Inspiring::quotes()->random())->explode('-');
                    @endphp

                    <div class="mt-auto">
                        <blockquote class="blockquote">
                            <h3 class="h5">&ldquo;{{ trim($message) }}&rdquo;</h3>
                            <footer class="blockquote-footer text-white-50">{{ trim($author) }}</footer>
                        </blockquote>
                    </div>
                </div>

                <div class="col-12 col-lg-6 d-flex align-items-center justify-content-center p-4">
                    <div class="w-100" style="max-width:420px;">
                        <div class="d-lg-none text-center mb-3">
                            <a href="{{ route('home') }}" class="d-inline-flex align-items-center" wire:navigate>
                                <span class="me-2">
                                    <x-app-logo-icon class="" />
                                </span>
                                <span class="visually-hidden">{{ config('app.name', 'Laravel') }}</span>
                            </a>
                        </div>

                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
