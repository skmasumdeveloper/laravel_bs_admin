<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Page;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function __invoke()
    {
        // Gather analytics data
        $analytics = [
            'total_users' => User::count(),
            'total_blogs' => Blog::count(),
            'total_categories' => Category::count(),
            'total_pages' => Page::count(),
            'total_roles' => Role::count(),
            'published_blogs' => Blog::where('status', 'published')->count(),
            'published_pages' => Page::where('is_published', true)->count(),
            'recent_users' => User::orderBy('created_at', 'desc')->take(5)->get(),
        ];

        return view('admin.dashboard', compact('analytics'));
    }
}
