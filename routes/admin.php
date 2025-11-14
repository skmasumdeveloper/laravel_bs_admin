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
use App\Http\Controllers\Admin\Categories\CategoryController;
use App\Http\Controllers\Admin\Blogs\BlogController;
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
                // Content management
                Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
                Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
                Route::put('categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
                Route::delete('categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
                
                Route::get('blogs', [BlogController::class, 'index'])->name('blogs.index');
                Route::post('blogs', [BlogController::class, 'store'])->name('blogs.store');
                Route::put('blogs/{id}', [BlogController::class, 'update'])->name('blogs.update');
                Route::delete('blogs/{id}', [BlogController::class, 'destroy'])->name('blogs.destroy');

                Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
                Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
                Route::delete('roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');
                
                Route::get('users/roles', [RoleAssignmentController::class, 'index'])->name('users.roles');
                Route::post('users/roles/assign', [RoleAssignmentController::class, 'assign'])->name('users.roles.assign');
                
                Route::get('users', [UserController::class, 'index'])->name('users.index');
                Route::post('users', [UserController::class, 'store'])->name('users.store');
                Route::put('users/{id}', [UserController::class, 'update'])->name('users.update');
                Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
                Route::get('users/search', [UserController::class, 'search'])->name('users.search');
                
                Route::resource('pages', AdminPageController::class)->except(['show']);
            });
        });
    });
});
