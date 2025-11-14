<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Display the marketing landing page.
     */
    public function __invoke()
    {
        return view('frontend.home');
    }
}
