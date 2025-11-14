<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Laravel\Fortify\Features;

class TwoFactorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);

        if (Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword')) {
            $this->middleware('password.confirm');
        }
    }

    public function __invoke()
    {
        abort_unless(Features::canManageTwoFactorAuthentication(), 404);

        return view('admin.settings.two-factor');
    }
}
