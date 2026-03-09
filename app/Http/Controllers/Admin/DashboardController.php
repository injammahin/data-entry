<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Import;
use App\Models\Record;
use App\Models\State;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStates = State::count();
        $totalImports = Import::count();
        $totalRecords = Record::count();

        $recentImports = Import::with('state')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalStates',
            'totalImports',
            'totalRecords',
            'recentImports'
        ));
    }
}