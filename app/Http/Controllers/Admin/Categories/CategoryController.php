<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function __invoke()
    {
        return view('admin.categories.index');
    }
}
