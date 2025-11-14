<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PageController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/', HomeController::class)->name('home');

    // Public-facing pages (about, contact, privacy, etc.)
    Route::get('{slug}', PageController::class)
        ->where('slug', '[A-Za-z0-9\-]+')
        ->name('pages.show');
});
