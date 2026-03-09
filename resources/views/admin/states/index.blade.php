@extends('layouts.admin')

@section('title', 'States')

@section('content')
    <style>
        .states-card {
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
            background: #fff;
        }

        .states-card-header {
            padding: 20px 24px;
            border-bottom: 1px solid #e2e8f0;
            background: linear-gradient(to right, #ffffff, #f8fafc);
        }

        .states-title {
            margin: 0;
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
        }

        .states-subtitle {
            margin-top: 5px;
            font-size: 13px;
            color: #64748b;
        }

        .states-table {
            margin-bottom: 0;
        }

        .states-table thead th {
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

        .states-table tbody td {
            padding: 16px;
            vertical-align: middle;
            border-color: #eef2f7;
        }

        .states-table tbody tr:hover {
            background: #f8fbff;
        }

        .state-id-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 38px;
            border-radius: 12px;
            background: #eff6ff;
            color: #2563eb;
            font-weight: 700;
        }

        .state-name {
            font-weight: 700;
            color: #0f172a;
        }

        .state-slug {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            background: #f1f5f9;
            color: #475569;
            font-size: 12px;
            font-weight: 600;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: 0.2s ease;
        }

        .action-btn-edit {
            background: #fef3c7;
            color: #b45309;
        }

        .action-btn-edit:hover {
            background: #fde68a;
            color: #92400e;
        }

        .action-btn-delete {
            background: #fee2e2;
            color: #dc2626;
        }

        .action-btn-delete:hover {
            background: #fecaca;
            color: #b91c1c;
        }

        .empty-state {
            padding: 50px 20px;
            text-align: center;
        }

        .empty-state-icon {
            width: 72px;
            height: 72px;
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

        .states-top-actions .btn {
            border-radius: 14px;
            padding: 10px 16px;
            font-weight: 600;
        }
    </style>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h1 class="states-title">States</h1>
            <div class="states-subtitle">Manage available states for CSV imports and data organization.</div>
        </div>

        <div class="states-top-actions">
            <a href="{{ route('admin.states.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus me-2"></i> Add State
            </a>
        </div>
    </div>

    <div class="states-card">
        <div class="states-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="mb-1 fw-bold text-dark">State List</h5>
                <div class="text-muted small">Total: {{ number_format($states->total()) }} state(s)</div>
            </div>
        </div>

        <div class="card-body p-0">
            @if($states->count())
                <div class="table-responsive">
                    <table class="table states-table align-middle">
                        <thead>
                            <tr>
                                <th width="90">#</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th width="160" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($states as $state)
                                <tr>
                                    <td>
                                        <span class="state-id-badge">{{ $state->id }}</span>
                                    </td>

                                    <td>
                                        <div class="state-name">{{ $state->name }}</div>
                                    </td>

                                    <td>
                                        <span class="state-slug">{{ $state->slug }}</span>
                                    </td>

                                    <td class="text-center">
                                        <div class="d-inline-flex align-items-center gap-2">
                                            <a href="{{ route('admin.states.edit', $state) }}"
                                               class="action-btn action-btn-edit text-decoration-none"
                                               title="Edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>

                                            <form action="{{ route('admin.states.destroy', $state) }}"
                                                  method="POST"
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="action-btn action-btn-delete"
                                                        title="Delete"
                                                        onclick="return confirm('Are you sure you want to delete this state?')">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-top">
                    {{ $states->links() }}
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <h5>No states found</h5>
                    <p>You have not added any states yet.</p>

                    <div class="mt-4">
                        <a href="{{ route('admin.states.create') }}" class="btn btn-primary">
                            <i class="fa-solid fa-plus me-2"></i> Add First State
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection