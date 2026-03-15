<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Import;
use App\Models\Record;
use App\Models\State;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStates = State::count();
        $totalImports = Import::count();
        $totalRecords = Record::count();

        // Get recent imports (the last 5 imports)
        $recentImports = Import::with('state')->latest()->take(5)->get();

        // Get the count of records for each state
        $statesWithRecordCount = State::withCount('records')->get();

        return view('admin.dashboard', compact(
            'totalStates',
            'totalImports',
            'totalRecords',
            'recentImports',
            'statesWithRecordCount' // Pass the state and record count data to the view
        ));
    }
}