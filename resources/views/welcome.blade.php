<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Vite-built CSS/JS (includes Bootstrap compiled from SCSS) -->
        @vite(['resources/css/app.scss', 'resources/js/app.js'])
    </head>
    <body class="bg-light text-dark d-flex p-4 p-lg-5 align-items-center justify-content-center min-vh-100 flex-column">
        <header class="w-100 mb-4">
            @if (Route::has('login'))
                <nav class="d-flex justify-content-end gap-2">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-outline-secondary btn-sm">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-link btn-sm">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-outline-primary btn-sm">Register</a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <div class="w-100 d-flex justify-content-center align-items-center flex-grow-1">
            <main class="container">
                <div class="row align-items-center gx-4">
                    <div class="col-12 col-lg-7">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h1 class="h4 mb-2">Let's get started</h1>
                                <p class="text-muted mb-3">Laravel has an incredibly rich ecosystem. We suggest starting with the following.</p>

                                <ul class="list-unstyled mb-3">
                                    <li class="d-flex align-items-start mb-2">
                                        <div class="me-3">
                                            <span class="rounded-circle bg-light border d-inline-block" style="width:18px;height:18px;"></span>
                                        </div>
                                        <div>
                                            Read the <a href="https://laravel.com/docs" target="_blank">Documentation</a>
                                        </div>
                                    </li>
                                    <li class="d-flex align-items-start mb-2">
                                        <div class="me-3">
                                            <span class="rounded-circle bg-light border d-inline-block" style="width:18px;height:18px;"></span>
                                        </div>
                                        <div>
                                            Watch tutorials at <a href="https://laracasts.com" target="_blank">Laracasts</a>
                                        </div>
                                    </li>
                                </ul>

                                <a href="https://cloud.laravel.com" target="_blank" class="btn btn-dark">Deploy now</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-5">
                        <div class="card bg-white shadow-sm">
                            <div class="card-body d-flex align-items-center justify-content-center p-3">
                                {{-- Laravel Logo --}}
                                <div class="w-100">
                                    <svg viewBox="0 0 438 104" fill="none" xmlns="http://www.w3.org/2000/svg" class="img-fluid">
                                        <path d="M17.2036 -3H0V102.197H49.5189V86.7187H17.2036V-3Z" fill="currentColor" />
                                        <path d="M110.256 41.6337C108.061 38.1275 104.945 35.3731 100.905 33.3681C96.8667 31.3647 92.8016 30.3618 88.7131 30.3618C83.4247 30.3618 78.5885 31.3389 74.201 33.2923C69.8111 35.2456 66.0474 37.928 62.9059 41.3333C59.7643 44.7401 57.3198 48.6726 55.5754 53.1293C53.8287 57.589 52.9572 62.274 52.9572 67.1813C52.9572 72.1925 53.8287 76.8995 55.5754 81.3069C57.3191 85.7173 59.7636 89.6241 62.9059 93.0293C66.0474 96.4361 69.8119 99.1155 74.201 101.069C78.5885 103.022 83.4247 103.999 88.7131 103.999C92.8016 103.999 96.8667 102.997 100.905 100.994C104.945 98.9911 108.061 96.2359 110.256 92.7282V102.195H126.563V32.1642H110.256V41.6337Z" fill="currentColor" />
                                        <path d="M242.805 41.6337C240.611 38.1275 237.494 35.3731 233.455 33.3681C229.416 31.3647 225.351 30.3618 221.262 30.3618C215.974 30.3618 211.138 31.3389 206.75 33.2923C202.36 35.2456 198.597 37.928 195.455 41.3333C192.314 44.7401 189.869 48.6726 188.125 53.1293C186.378 57.589 185.507 62.274 185.507 67.1813C185.507 72.1925 186.378 76.8995 188.125 81.3069C189.868 85.7173 192.313 89.6241 195.455 93.0293C198.597 96.4361 202.361 99.1155 206.75 101.069C211.138 103.022 215.974 103.999 221.262 103.999C225.351 103.999 229.416 102.997 233.455 100.994C237.494 98.9911 240.611 96.2359 242.805 92.7282V102.195H259.112V32.1642H242.805V41.6337Z" fill="currentColor" />
                                        <path d="M438 -3H421.694V102.197H438V-3Z" fill="currentColor" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        @if (Route::has('login'))
            <div class="d-none d-lg-block" style="height:58px;"></div>
        @endif

        {{-- Bootstrap JS removed (bundled via Vite if needed) --}}
    </body>
</html>

