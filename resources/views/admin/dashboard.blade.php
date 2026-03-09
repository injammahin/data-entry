@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <h1 class="mb-4">Admin Dashboard</h1>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Total States</h6>
                    <h2 class="mb-0">{{ number_format($totalStates) }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Total Imports</h6>
                    <h2 class="mb-0">{{ number_format($totalImports) }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Total Records</h6>
                    <h2 class="mb-0">{{ number_format($totalRecords) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <strong>Recent Imports</strong>
        </div>
        <div class="card-body">
            @if($recentImports->count())
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>State</th>
                                <th>File</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Processed</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentImports as $import)
                                <tr>
                                    <td>{{ $import->id }}</td>
                                    <td>{{ $import->state->name ?? '-' }}</td>
                                    <td>{{ $import->original_name }}</td>
                                    <td>{{ ucfirst($import->status) }}</td>
                                    <td>{{ number_format($import->total_rows) }}</td>
                                    <td>{{ number_format($import->processed_rows) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="mb-0">No recent imports found.</p>
            @endif
        </div>
    </div>
@endsection