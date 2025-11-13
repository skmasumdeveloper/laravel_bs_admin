<?php

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function __invoke()
    {
        return view('admin.roles.index');
    }
}
