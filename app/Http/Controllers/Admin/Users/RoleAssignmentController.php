<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;

class RoleAssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function __invoke()
    {
        return view('admin.users.assign-roles');
    }
}
