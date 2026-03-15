@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <h1 class="mb-4 text-3xl font-semibold">Admin Dashboard</h1>

    <div class="row g-3 mb-4">
        <!-- Total States Card -->
        <div class="col-md-4">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body">
                    <h6 class="text-muted">Total States</h6>
                    <h2 class="mb-0 text-primary font-bold">{{ number_format($totalStates) }}</h2>
                </div>
            </div>
        </div>

        <!-- Total Imports Card -->
        <div class="col-md-4">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body">
                    <h6 class="text-muted">Total Imports</h6>
                    <h2 class="mb-0 text-primary font-bold">{{ number_format($totalImports) }}</h2>
                </div>
            </div>
        </div>

        <!-- Total Records Card -->
        <div class="col-md-4">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body">
                    <h6 class="text-muted">Total Records</h6>
                    <h2 class="mb-0 text-primary font-bold">
                        {{ formatNumber($totalRecords) }}
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Imports Table -->
    <div class="card shadow-lg">
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
                                    <td>
                                        <span class="badge {{ $import->status == 'completed' ? 'bg-success' : 'bg-warning' }}">
                                            {{ ucfirst($import->status) }}
                                        </span>
                                    </td>
                                    <td>{{ formatNumber($import->total_rows) }}</td>
                                    <td>{{ formatNumber($import->processed_rows) }}</td>
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

    <!-- States and Records Count -->
    <div class="card shadow-lg mt-4">
        <div class="card-header bg-white">
            <strong>States and Record Counts</strong>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>State</th>
                            <th>Records</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($statesWithRecordCount as $index => $state)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $state->name }}</td>
                                <td>{{ number_format($state->records_count) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        /* Custom styles */
        .card {
            border-radius: 10px;
        }

        .card-header {
            font-size: 1.2rem;
            font-weight: 600;
        }

        .badge {
            font-size: 0.9rem;
            font-weight: 500;
        }
    </style>
@endpush

@php
    function formatNumber($number)
    {
        if ($number >= 1000000) {
            return number_format($number / 1000000, 1) . 'M';
        }
        return number_format($number);
    }
@endphp