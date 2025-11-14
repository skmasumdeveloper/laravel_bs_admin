<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\App;

class DatabaseMigrationController extends Controller
{
    public function __invoke(Request $request)
    {
        // Only allow migration in non-production environments for security
        if (App::environment('production')) {
            abort(403, 'Database migration via URL is disabled in production.');
        }

        try {
            // Run migrations
            Artisan::call('migrate', ['--force' => true]);
            $output = Artisan::output();

            return response()->json([
                'success' => true,
                'message' => 'Database migration completed successfully.',
                'output' => $output,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database migration failed.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
