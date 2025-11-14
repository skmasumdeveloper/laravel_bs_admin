<?php

namespace App\Http\Controllers\Admin\Blogs;

use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function __invoke()
    {
        return view('admin.blogs.index');
    }
}
