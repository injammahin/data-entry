<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessCsvImportJob;
use App\Models\Import;
use App\Models\State;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function index()
    {
        $imports = Import::with(['state', 'user'])
            ->latest()
            ->paginate(10);

        return view('admin.imports.index', compact('imports'));
    }

    public function create()
    {
        $states = State::orderBy('name')->get();

        return view('admin.imports.create', compact('states'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'state_id' => ['required', 'exists:states,id'],
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:512000'],
        ]);

        $file = $request->file('csv_file');
        $path = $file->store('imports');
        $fullPath = storage_path('app/' . $path);

        $fileHash = hash_file('sha256', $fullPath);

        $existingImport = Import::where('state_id', $request->state_id)
            ->where('file_hash', $fileHash)
            ->first();

        if ($existingImport) {
            return back()->withErrors([
                'csv_file' => 'This same file has already been uploaded for the selected state.',
            ])->withInput();
        }

        $handle = fopen($fullPath, 'r');

        if ($handle === false) {
            return back()->withErrors([
                'csv_file' => 'Unable to open uploaded file.',
            ])->withInput();
        }

        $headers = fgetcsv($handle);
        fclose($handle);

        if (!$headers || count($headers) === 0) {
            return back()->withErrors([
                'csv_file' => 'CSV header row is missing or invalid.',
            ])->withInput();
        }

        $headers = array_map(fn ($header) => trim((string) $header), $headers);

        $import = Import::create([
            'state_id' => $request->state_id,
            'file_name' => $path,
            'original_name' => $file->getClientOriginalName(),
            'file_hash' => $fileHash,
            'headers' => $headers,
            'status' => 'pending',
            'created_by' => auth()->id(),
        ]);

        ProcessCsvImportJob::dispatch($import->id);

        return redirect()
            ->route('admin.imports.index')
            ->with('success', 'CSV uploaded successfully. Import job started.');
    }

    public function show(Import $import)
    {
        $import->load('state', 'user');

        $records = $import->records()
            ->latest()
            ->paginate(20);

        return view('admin.imports.show', compact('import', 'records'));
    }

    public function records(Request $request, Import $import)
    {
        $perPage = (int) $request->get('per_page', 30);

        if (!in_array($perPage, [30, 50, 100])) {
            $perPage = 30;
        }

        $records = $import->records()
            ->orderBy('id')
            ->paginate($perPage)
            ->appends($request->query());

        $headers = is_array($import->headers) ? $import->headers : [];

        return view('admin.imports.records', compact('import', 'records', 'headers', 'perPage'));
    }

    public function retry(Import $import)
    {
        if (!in_array($import->status, ['failed', 'completed'])) {
            return back()->withErrors([
                'retry' => 'Only failed or completed imports can be retried.',
            ]);
        }

        $import->records()->delete();

        $import->update([
            'status' => 'pending',
            'processed_rows' => 0,
            'successful_rows' => 0,
            'skipped_rows' => 0,
            'total_rows' => 0,
            'error_message' => null,
            'started_at' => null,
            'completed_at' => null,
        ]);

        ProcessCsvImportJob::dispatch($import->id);

        return back()->with('success', 'Import retry started.');
    }
}