@extends('layouts.admin')

@section('title', 'Imports')

@section('content')
    <style>
        .imports-card {
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
            background: #fff;
        }

        .imports-card-header {
            padding: 20px 24px;
            border-bottom: 1px solid #e2e8f0;
            background: linear-gradient(to right, #ffffff, #f8fafc);
        }

        .imports-title {
            margin: 0;
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
        }

        .imports-subtitle {
            margin-top: 5px;
            font-size: 13px;
            color: #64748b;
        }

        .imports-table {
            margin-bottom: 0;
        }

        .imports-table thead th {
            background: #f8fafc;
            color: #475569;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-weight: 700;
            border-bottom: 1px solid #e2e8f0;
            padding: 14px 16px;
            white-space: nowrap;
        }

        .imports-table tbody td {
            padding: 16px;
            vertical-align: middle;
            border-color: #eef2f7;
            white-space: nowrap;
        }

        .imports-table tbody tr:hover {
            background: #f8fbff;
        }

        .import-id-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 42px;
            height: 38px;
            border-radius: 12px;
            background: #eff6ff;
            color: #2563eb;
            font-weight: 700;
        }

        .state-pill {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            background: #f1f5f9;
            color: #334155;
            font-size: 12px;
            font-weight: 700;
        }

        .file-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #2563eb;
            font-weight: 700;
            text-decoration: none;
        }

        .file-link:hover {
            color: #1d4ed8;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-processing {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-completed {
            background: #dcfce7;
            color: #166534;
        }

        .status-failed {
            background: #fee2e2;
            color: #b91c1c;
        }

        .metric-box {
            display: inline-flex;
            flex-direction: column;
            min-width: 70px;
        }

        .metric-value {
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
        }

        .metric-label {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            margin-top: 3px;
        }

        .user-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #334155;
        }

        .user-chip .icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e0e7ff;
            color: #3730a3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
        }

        .time-text {
            font-size: 13px;
            color: #334155;
            line-height: 1.4;
            white-space: normal;
        }

        .time-text .muted-line {
            display: block;
            font-size: 11px;
            color: #94a3b8;
            margin-top: 2px;
        }

        .error-text {
            font-size: 12px;
            color: #dc2626;
            margin-top: 6px;
            white-space: normal;
            max-width: 220px;
        }

        .empty-state {
            padding: 56px 20px;
            text-align: center;
        }

        .empty-state-icon {
            width: 74px;
            height: 74px;
            border-radius: 20px;
            margin: 0 auto 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eff6ff;
            color: #2563eb;
            font-size: 28px;
        }

        .empty-state h5 {
            font-weight: 700;
            color: #0f172a;
        }

        .empty-state p {
            color: #64748b;
            margin-bottom: 0;
        }

        .imports-top-actions .btn {
            border-radius: 14px;
            padding: 10px 16px;
            font-weight: 600;
        }

        .imports-summary {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        .imports-summary-card {
            min-width: 140px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 16px 18px;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.03);
        }

        .imports-summary-card .value {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
        }

        .imports-summary-card .label {
            margin-top: 4px;
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-weight: 700;
        }
    </style>

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <h1 class="imports-title">Imports</h1>
            <div class="imports-subtitle">Manage uploaded CSV files, track progress, and review import results.</div>
        </div>

        <div class="imports-top-actions">
            <a href="{{ route('admin.imports.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-upload me-2"></i> Upload CSV
            </a>
        </div>
    </div>

    <div class="imports-summary mb-4">
        <div class="imports-summary-card">
            <div class="value">{{ number_format($imports->total()) }}</div>
            <div class="label">Total Imports</div>
        </div>

        <div class="imports-summary-card">
            <div class="value">{{ number_format($imports->where('status', 'completed')->count()) }}</div>
            <div class="label">Completed</div>
        </div>

        <div class="imports-summary-card">
            <div class="value">{{ number_format($imports->where('status', 'processing')->count()) }}</div>
            <div class="label">Processing</div>
        </div>

        <div class="imports-summary-card">
            <div class="value">{{ number_format($imports->where('status', 'failed')->count()) }}</div>
            <div class="label">Failed</div>
        </div>
    </div>

    <div class="imports-card">
        <div class="imports-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="mb-1 fw-bold text-dark">Import History</h5>
                <div class="text-muted small">Track every uploaded file and processing status.</div>
            </div>
        </div>

        <div class="card-body p-0">
            @if($imports->count())
                <div class="table-responsive">
                    <table class="table imports-table align-middle">
                        <thead>
                            <tr>
                                <th width="90">#</th>
                                <th>State</th>
                                <th>Original File</th>
                                <th>Status</th>
                                <th>Total Rows</th>
                                <th>Processed</th>
                                <th>Successful</th>
                                <th>Skipped</th>
                                <th>Uploaded By</th>
                                <th>Started</th>
                                <th>Completed</th>
                                <th>Uploaded At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($imports as $import)
                                @php
                                    $statusClass = match($import->status) {
                                        'pending' => 'status-pending',
                                        'processing' => 'status-processing',
                                        'completed' => 'status-completed',
                                        'failed' => 'status-failed',
                                        default => 'status-pending',
                                    };

                                    $statusIcon = match($import->status) {
                                        'pending' => 'fa-regular fa-clock',
                                        'processing' => 'fa-solid fa-rotate',
                                        'completed' => 'fa-solid fa-circle-check',
                                        'failed' => 'fa-solid fa-circle-xmark',
                                        default => 'fa-regular fa-clock',
                                    };

                                    $userInitial = $import->user?->name ? strtoupper(substr($import->user->name, 0, 1)) : 'A';
                                @endphp

                                <tr>
                                    <td>
                                        <span class="import-id-badge">{{ $import->id }}</span>
                                    </td>

                                    <td>
                                        <span class="state-pill">{{ $import->state->name ?? '-' }}</span>
                                    </td>

                                    <td>
                                        <a href="{{ route('admin.imports.show', $import) }}" class="file-link">
                                            <i class="fa-solid fa-file-csv"></i>
                                            <span>{{ $import->original_name }}</span>
                                        </a>
                                    </td>

                                    <td>
                                        <span class="status-badge {{ $statusClass }}">
                                            <i class="{{ $statusIcon }}"></i>
                                            {{ ucfirst($import->status) }}
                                        </span>

                                        @if($import->status === 'failed' && $import->error_message)
                                            <div class="error-text">{{ $import->error_message }}</div>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="metric-box">
                                            <span class="metric-value">{{ number_format($import->total_rows) }}</span>
                                            <span class="metric-label">Rows</span>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="metric-box">
                                            <span class="metric-value">{{ number_format($import->processed_rows) }}</span>
                                            <span class="metric-label">Processed</span>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="metric-box">
                                            <span class="metric-value">{{ number_format($import->successful_rows) }}</span>
                                            <span class="metric-label">Success</span>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="metric-box">
                                            <span class="metric-value">{{ number_format($import->skipped_rows) }}</span>
                                            <span class="metric-label">Skipped</span>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="user-chip">
                                            <span class="icon">{{ $userInitial }}</span>
                                            <span>{{ $import->user->name ?? '-' }}</span>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="time-text">
                                            {{ $import->started_at ? $import->started_at->format('d M Y') : '-' }}
                                            @if($import->started_at)
                                                <span class="muted-line">{{ $import->started_at->format('h:i A') }}</span>
                                            @endif
                                        </div>
                                    </td>

                                    <td>
                                        <div class="time-text">
                                            {{ $import->completed_at ? $import->completed_at->format('d M Y') : '-' }}
                                            @if($import->completed_at)
                                                <span class="muted-line">{{ $import->completed_at->format('h:i A') }}</span>
                                            @endif
                                        </div>
                                    </td>

                                    <td>
                                        <div class="time-text">
                                            {{ $import->created_at->format('d M Y') }}
                                            <span class="muted-line">{{ $import->created_at->format('h:i A') }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-top">
                    {{ $imports->links() }}
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fa-solid fa-file-import"></i>
                    </div>
                    <h5>No imports found</h5>
                    <p>You have not uploaded any CSV file yet.</p>

                    <div class="mt-4">
                        <a href="{{ route('admin.imports.create') }}" class="btn btn-primary">
                            <i class="fa-solid fa-upload me-2"></i> Upload First CSV
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection