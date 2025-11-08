<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {   Gate::authorize('read_calendar');
        return view('admin.calendars.index');
    }
}
