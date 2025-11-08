<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\Request;

class DashboardContoller extends Controller
{
    public function index(){
        Gate::authorize('access_dashboard');
        return view('admin.dashboard');
    }
}
