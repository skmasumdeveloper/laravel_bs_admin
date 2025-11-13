<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\PageController as AdminPageController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    // Role & Permission management (admin only)
    Volt::route('roles', 'roles.manage-roles')
        ->middleware(['role:admin'])
        ->name('roles.index');

    Volt::route('users/roles', 'users.assign-roles')
        ->middleware(['role:admin'])
        ->name('users.roles');

    // Users CRUD (admin)
    Volt::route('users', 'users.index')
        ->middleware(['role:admin'])
        ->name('users.index');

    // Admin Pages management
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('pages', AdminPageController::class)->except(['show']);
    });
});

// Public-facing pages (about, contact, privacy, etc.)
// NOTE: Placed last so it doesn't shadow other routes. It will return 404
// for non-existing slugs and let other routes be matched first.
Route::get('{slug}', [PageController::class, 'show'])->name('pages.show')
    ->where('slug', '[A-Za-z0-9\-]+');
