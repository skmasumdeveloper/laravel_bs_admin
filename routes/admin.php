<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\Roles\RoleController;
use App\Http\Controllers\Admin\Settings\AppearanceController;
use App\Http\Controllers\Admin\Settings\PasswordController;
use App\Http\Controllers\Admin\Settings\ProfileController;
use App\Http\Controllers\Admin\Settings\TwoFactorController;
use App\Http\Controllers\Admin\Users\RoleAssignmentController;
use App\Http\Controllers\Admin\Users\UserController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::middleware('web')->group(function () {
    Route::prefix('admin')->as('admin.')->group(function () {
        Route::middleware(['auth', 'verified'])->group(function () {
            Route::redirect('/', '/admin/dashboard')->name('root');
            Route::get('dashboard', DashboardController::class)->name('dashboard');
        });

        Route::middleware(['auth'])->group(function () {
            Route::redirect('settings', 'settings/profile')->name('settings');

            Route::prefix('settings')->group(function () {
                Route::get('profile', ProfileController::class)->name('settings.profile');
                Route::get('password', PasswordController::class)->name('settings.password');
                Route::get('appearance', AppearanceController::class)->name('settings.appearance');

                if (Features::canManageTwoFactorAuthentication()) {
                    Route::get('two-factor', TwoFactorController::class)
                        ->name('settings.two-factor');
                }
            });

            Route::middleware('role:admin')->group(function () {
                Route::get('roles', RoleController::class)->name('roles.index');
                Route::get('users/roles', RoleAssignmentController::class)->name('users.roles');
                Route::get('users', UserController::class)->name('users.index');
                Route::resource('pages', AdminPageController::class)->except(['show']);
            });
        });
    });
});
