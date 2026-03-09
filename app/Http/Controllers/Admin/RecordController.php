<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Import;
use App\Models\Record;
use App\Models\State;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 30);

        if (!in_array($perPage, [30, 50, 100], true)) {
            $perPage = 30;
        }

        $states = State::orderBy('name')->get();
        $imports = Import::with('state')->latest()->get();

        $query = Record::with(['state', 'import'])->orderBy('id');

        if ($request->filled('state_id')) {
            $query->where('state_id', $request->state_id);
        }

        if ($request->filled('import_id')) {
            $query->where('import_id', $request->import_id);
        }

        if ($request->filled('keyword')) {
            $keyword = trim($request->keyword);
            $query->where('data_json', 'like', '%' . $keyword . '%');
        }

        $records = $query->paginate($perPage)->appends($request->query());

        $headers = [];

        if ($request->filled('import_id')) {
            $selectedImport = $imports->firstWhere('id', (int) $request->import_id);

            if ($selectedImport && is_array($selectedImport->headers)) {
                $headers = $selectedImport->headers;
            }
        }

        if (empty($headers) && $records->count()) {
            $firstRecord = $records->first();

            if (is_array($firstRecord->data_json)) {
                $headers = array_keys($firstRecord->data_json);
            }
        }

        return view('admin.records.index', compact(
            'records',
            'states',
            'imports',
            'headers',
            'perPage'
        ));
    }
}