@extends('layouts.admin')

@section('title', 'Import Details')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Import #{{ $import->id }}</h1>

        <div class="d-flex gap-2">
            @if(in_array($import->status, ['failed', 'completed']))
                <form action="{{ route('admin.imports.retry', $import) }}" method="POST">
                    @csrf
                    <button class="btn btn-warning" onclick="return confirm('Retry this import? Existing imported records for this batch will be deleted.')">
                        Retry Import
                    </button>
                </form>
            @endif

            <a href="{{ route('admin.imports.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <strong>State:</strong><br>
                    {{ $import->state->name ?? '-' }}
                </div>
                <div class="col-md-4">
                    <strong>Original File:</strong><br>
                    {{ $import->original_name }}
                </div>
                <div class="col-md-4">
                    <strong>Status:</strong><br>
                    {{ ucfirst($import->status) }}
                </div>
                <div class="col-md-4">
                    <strong>Total Rows:</strong><br>
                    {{ number_format($import->total_rows) }}
                </div>
                <div class="col-md-4">
                    <strong>Processed Rows:</strong><br>
                    {{ number_format($import->processed_rows) }}
                </div>
                <div class="col-md-4">
                    <strong>Successful Rows:</strong><br>
                    {{ number_format($import->successful_rows) }}
                </div>
                <div class="col-md-4">
                    <strong>Skipped Rows:</strong><br>
                    {{ number_format($import->skipped_rows) }}
                </div>
                <div class="col-md-4">
                    <strong>Uploaded By:</strong><br>
                    {{ $import->user->name ?? '-' }}
                </div>
                <div class="col-md-4">
                    <strong>Started At:</strong><br>
                    {{ $import->started_at ? $import->started_at->format('d M Y h:i A') : '-' }}
                </div>
                <div class="col-md-4">
                    <strong>Completed At:</strong><br>
                    {{ $import->completed_at ? $import->completed_at->format('d M Y h:i A') : '-' }}
                </div>
            </div>

            <hr>

            <strong>Headers:</strong>
            <div class="mt-2">
                @if(is_array($import->headers))
                    @foreach($import->headers as $header)
                        <span class="badge bg-dark me-1 mb-1">{{ $header }}</span>
                    @endforeach
                @endif
            </div>

            @if($import->error_message)
                <hr>
                <div class="alert alert-danger mb-0">
                    {{ $import->error_message }}
                </div>
            @endif
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <strong>Imported Records</strong>
        </div>
        <div class="card-body">
            @if($records->count())
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Row Number</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $record)
                                <tr>
                                    <td>{{ $record->id }}</td>
                                    <td>{{ $record->row_number }}</td>
                                    <td>
                                        @foreach($record->data_json as $key => $value)
                                            <div class="mb-1">
                                                <strong>{{ $key }}:</strong> {{ $value }}
                                            </div>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $records->links() }}
            @else
                <p class="mb-0">No records found for this import.</p>
            @endif
        </div>
    </div>
@endsection