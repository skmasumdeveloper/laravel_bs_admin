@extends('frontend.layouts.app')

@section('title', config('app.name'))

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center gy-4">
                <div class="col-12 col-lg-6">
                    <p class="text-uppercase small text-muted mb-1">{{ __('Modern Admin Starter') }}</p>
                    <h1 class="display-6 fw-semibold mb-3">{{ __('Build secure admin portals with Livewire superpowers') }}</h1>
                    <p class="text-muted mb-4">{{ __('Quickly scaffold new features with reusable UI blocks, Livewire-powered interactions, and a clean separation between the marketing site and the administration suite.') }}</p>

                    <div class="d-flex flex-wrap gap-2">
                        @auth
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-lg">{{ __('Go to Admin') }}</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">{{ __('Sign in') }}</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">{{ __('Create account') }}</a>
                            @endif
                        @endauth
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h2 class="h5 mb-3">{{ __('Why this starter?') }}</h2>
                            <ul class="list-unstyled mb-0">
                                <li class="d-flex gap-3 mb-3">
                                    <div class="text-primary">⚡️</div>
                                    <div>
                                        <strong>{{ __('Livewire everywhere') }}</strong>
                                        <p class="mb-0 text-muted">{{ __('All data mutations happen without page reloads using dedicated Livewire component classes.') }}</p>
                                    </div>
                                </li>
                                <li class="d-flex gap-3 mb-3">
                                    <div class="text-primary">🧱</div>
                                    <div>
                                        <strong>{{ __('Reusable building blocks') }}</strong>
                                        <p class="mb-0 text-muted">{{ __('Copy ready-made layouts, tables, forms, and modals to spin up new features fast.') }}</p>
                                    </div>
                                </li>
                                <li class="d-flex gap-3">
                                    <div class="text-primary">🔐</div>
                                    <div>
                                        <strong>{{ __('Fortified security') }}</strong>
                                        <p class="mb-0 text-muted">{{ __('Role/permission management, 2FA, and session control ship in the admin area by default.') }}</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
