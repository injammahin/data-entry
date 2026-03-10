<?php

namespace App\Http\Controllers\Lead;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('lead.dashboard');
    }
}