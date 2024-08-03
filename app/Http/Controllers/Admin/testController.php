<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class testController extends Controller
{
    public function login()
    {
        return view('admin-views.admin-design.login');
    }
}
