<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;

class AppearanceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function __invoke()
    {
        return view('admin.settings.appearance');
    }
}
