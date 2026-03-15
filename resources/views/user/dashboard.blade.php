@extends('layouts.user')

@section('title', 'User Dashboard')

@push('styles')
    <style>
        :root {
            --bg: #f4f7fa;
            /* Light background */
            --card-bg: rgba(255, 255, 255, .92);
            --line: rgba(14, 30, 63, .08);
            --text: #333;
            --primary: #084b90;
            /* Dark blue */
            --secondary: #ff8c3d;
            --accent: #2ecc71;
            --shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
            --border-radius: 12px;
            /* Slightly rounded edges */
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        /* Header */
        .dashboard-header {
            background: var(--primary);
            color: #fff;
            padding: 30px 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            margin-bottom: 30px;
        }

        .dashboard-header h2 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .dashboard-header p {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.7);
        }

        /* Quick Action Cards */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            margin-top: 30px;
        }

        .quick-card {
            background: var(--card-bg);
            padding: 24px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            text-align: start;
            transition: transform 0.3s ease-in-out;
            position: relative;
            overflow: hidden;
        }

        .quick-card:hover {
            transform: translateY(-8px);
        }

        .quick-card .icon {
            font-size: 36px;
            color: #fff;
            border-radius: 50%;
            width: 70px;
            height: 70px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #5c9fdd, #2d89d0);
            box-shadow:
                0 0 0 8px rgba(85, 164, 255, .10),
                0 18px 30px rgba(55, 125, 255, .22);
            transition: transform 0.3s ease;
        }

        .quick-card .icon:hover {
            transform: scale(1.1);
        }

        .quick-card h4 {
            font-size: 20px;
            margin-bottom: 10px;
            font-weight: 700;
            color: #084b90;
        }

        .quick-card p {
            font-size: 14px;
            color: #777;
            line-height: 1.75;
            max-width: 240px;
            margin: 0 auto;
        }

        .quick-card.disabled {
            opacity: .7;
        }

        /* Hero Stats */
        .hero-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .stat-card {
            background: var(--card-bg);
            padding: 24px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }

        .stat-card .label {
            font-size: 14px;
            color: #777;
            margin-bottom: 8px;
        }

        .stat-card .value {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary);
        }

        /* Table Panel */
        .table-panel {
            margin-top: 30px;
            background: var(--card-bg);
            padding: 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .table-header h3 {
            font-size: 26px;
            font-weight: 700;
            color: var(--primary);
        }

        .table-header .badge {
            background-color: var(--secondary);
            color: #fff;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 12px;
            text-align: left;
            font-size: 14px;
            border-bottom: 1px solid #f0f0f0;
        }

        table th {
            background: #f7f7f7;
            font-weight: 700;
        }

        table tbody tr:hover {
            background: rgba(74, 168, 255, 0.1);
        }

        /* Buttons */
        .btn-primary-soft {
            background: var(--primary);
            color: #fff;
            padding: 12px 18px;
            border-radius: 50px;
            font-weight: 600;
            transition: 0.3s ease;
        }

        .btn-primary-soft:hover {
            background: #1d5379;
        }

        .table-wrap {
            overflow: auto;
            border-radius: 16px;
        }

        @media (max-width: 1200px) {
            .dashboard-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .hero-stats {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .table-header {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h2>Welcome, {{ auth()->user()->name }}!</h2>
        <p>Your dashboard overview and quick actions at a glance</p>
    </div>

    <!-- Quick Actions Section -->
    <div class="dashboard-grid ">
        @foreach($quickActions as $action)
            <a href="{{ $action['url'] }}" class="quick-card {{ !$action['active'] ? 'disabled' : '' }}" @if(!$action['active'])
            onclick="return false;" @endif>
                <div class="icon">
                    <i class="fa-solid {{ $action['icon'] }}"></i>
                </div>
                <h4>{{ $action['title'] }}</h4>
                <p>{{ $action['description'] }}</p>
            </a>
        @endforeach
    </div>

    <!-- Table Section -->
    <div class="table-panel">
        <div class="table-header">
            <h3>Saved Searches</h3>
            <span class="badge">{{ count($savedSearches) }} Items</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Count</th>
                        <th>Database</th>
                        <th>Date Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($savedSearches as $search)
                        <tr>
                            <td>
                                <a href="{{ route('saved-lists.open', $search) }}" class="table-link">{{ $search['name'] }}</a>
                            </td>
                            <td>{{ $search['count'] }}</td>
                            <td>{{ $search['database'] }}</td>
                            <td>{{ $search['date_created'] }}</td>
                            <td>
                                <div class="icon-actions">
                                    <i class="fa-solid fa-at"></i>
                                    <i class="fa-solid fa-envelope"></i>
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection