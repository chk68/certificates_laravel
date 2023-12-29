<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;


class IndexController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        return view('admin.main.index');
    }
}
