<?php

use App\Http\Controllers\DatabaseMigrationController;
use Illuminate\Support\Facades\Route;

// Database migration route (only for non-production environments)
Route::get('db-migrate', DatabaseMigrationController::class)->name('db.migrate');

require __DIR__.'/admin.php';
require __DIR__.'/frontend.php';
