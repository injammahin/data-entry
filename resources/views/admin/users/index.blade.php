@extends('layouts.admin')

@section('title', 'Users')

@section('content')
    <style>
        .users-card {
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
            background: #fff;
        }

        .users-card-header {
            padding: 20px 24px;
            border-bottom: 1px solid #e2e8f0;
            background: linear-gradient(to right, #ffffff, #f8fafc);
        }

        .users-title {
            margin: 0;
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
        }

        .users-subtitle {
            margin-top: 5px;
            font-size: 13px;
            color: #64748b;
        }

        .users-table thead th {
            background: #f8fafc;
            color: #475569;
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 700;
            padding: 14px 16px;
            white-space: nowrap;
        }

        .users-table tbody td {
            padding: 16px;
            vertical-align: middle;
            border-color: #eef2f7;
        }

        .users-table tbody tr:hover {
            background: #f8fbff;
        }

        .role-badge {
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            display: inline-block;
        }

        .role-admin {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .role-user {
            background: #dcfce7;
            color: #166534;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
        }

        .action-btn-edit {
            background: #fef3c7;
            color: #b45309;
        }

        .action-btn-delete {
            background: #fee2e2;
            color: #dc2626;
        }
    </style>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h1 class="users-title">Users</h1>
            <div class="users-subtitle">Create and manage admin and lead users.</div>
        </div>

        <a href="{{ route('admin.users.create') }}" class="btn btn-primary rounded-3">
            <i class="fa-solid fa-user-plus me-2"></i> Add User
        </a>
    </div>

    <div class="users-card">
        <div class="users-card-header">
            <h5 class="mb-1 fw-bold text-dark">User List</h5>
            <div class="text-muted small">Total: {{ number_format($users->total()) }} user(s)</div>
        </div>

        <div class="card-body p-0">
            @if($users->count())
                <div class="table-responsive">
                    <table class="table users-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th width="150" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td><strong>{{ $user->name }}</strong></td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="role-badge {{ $user->role === 'admin' ? 'role-admin' : 'role-user' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-inline-flex gap-2">
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                               class="action-btn action-btn-edit text-decoration-none"
                                               title="Edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>

                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="action-btn action-btn-delete"
                                                        title="Delete"
                                                        onclick="return confirm('Are you sure you want to delete this user?')">
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
                    {{ $users->links() }}
                </div>
            @else
                <div class="p-5 text-center">
                    <i class="fa-solid fa-users fa-2x text-primary mb-3"></i>
                    <h5>No users found</h5>
                    <p class="text-muted">Create your first user account.</p>
                </div>
            @endif
        </div>
    </div>
@endsection