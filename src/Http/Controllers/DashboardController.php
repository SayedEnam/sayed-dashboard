<?php

namespace Sayed\SayedDashboard\Http\Controllers;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('sayed-dashboard::index');
    }
}