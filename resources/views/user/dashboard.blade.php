@extends('layouts.user')

@section('title', 'User Dashboard')

@push('styles')
    <style>
        :root {
            --bg: #f3f4f8;
            --card-bg: #ffffff;
            --line: #ebeef5;
            --text: #2e3553;
            --text-soft: #666f86;
            --primary: #24376a;
            --secondary: #ff8c3d;
            --accent: #2ecc71;
            --shadow: 0 16px 40px rgba(42, 53, 102, 0.08), 0 4px 12px rgba(42, 53, 102, 0.04);
            --shadow-hover: 0 24px 48px rgba(42, 53, 102, 0.14), 0 8px 18px rgba(42, 53, 102, 0.08);
            --border-radius: 18px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        .dashboard-header {
            background: linear-gradient(135deg, #24376a 0%, #3555a9 100%);
            color: #fff;
            padding: 30px 24px;
            border-radius: 24px;
            box-shadow: var(--shadow);
            margin-bottom: 34px;
        }

        .dashboard-header h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .dashboard-header p {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.8);
            margin: 0;
        }

        /* Quick Action Cards */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 28px;
            margin-top: 10px;
            align-items: stretch;
        }

        .quick-card {
            background: #fff;
            border: 1px solid #eceff5;
            border-radius: 26px;
            padding: 24px 24px 30px;
            box-shadow: 0 14px 32px rgba(68, 76, 112, 0.08);
            text-align: center;
            transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
            position: relative;
            overflow: hidden;
            min-height: 420px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            text-decoration: none;
        }

        .quick-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.55) 0%, rgba(255, 255, 255, 0) 25%);
            pointer-events: none;
        }

        .quick-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-hover);
            border-color: #dde4f1;
        }

        .quick-card.disabled {
            opacity: .72;
        }

        .quick-card.disabled:hover {
            transform: none;
            box-shadow: 0 14px 32px rgba(68, 76, 112, 0.08);
        }

        .quick-media {
            height: 190px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin-bottom: 4px;
        }

        .quick-card h4 {
            font-size: 21px;
            line-height: 1.2;
            font-weight: 800;
            margin: 4px 0 16px;
            letter-spacing: -0.01em;
        }

        .quick-card p {
            font-size: 15px;
            line-height: 1.9;
            color: var(--text-soft);
            max-width: 230px;
            margin: 0 auto;
        }

        /* base illustration */
        .action-visual {
            position: relative;
            width: 138px;
            height: 138px;
            border-radius: 50%;
            display: block;
            isolation: isolate;
        }

        .action-visual .piece {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }

        .action-visual i {
            line-height: 1;
        }

        /* Import Lists */
        .theme-import h4 {
            color: #28357b;
        }

        .visual-import {
            background: radial-gradient(circle at 35% 30%, #58d8ff 0%, #2d88ff 48%, #2150d8 100%);
            box-shadow:
                inset 0 8px 18px rgba(255, 255, 255, 0.22),
                0 22px 28px rgba(54, 115, 233, 0.22);
        }

        .visual-import::before {
            content: "";
            position: absolute;
            inset: 18px;
            border-radius: 50%;
            background: radial-gradient(circle at 65% 35%, rgba(255, 255, 255, 0.22), transparent 46%);
            z-index: 0;
        }

        .visual-import .folder-back {
            left: 19px;
            top: 51px;
            color: #0d4ac9;
            font-size: 56px;
            transform: rotate(-3deg);
            filter: drop-shadow(0 12px 12px rgba(9, 54, 165, 0.22));
            z-index: 1;
        }

        .visual-import .folder-front {
            left: 34px;
            top: 43px;
            color: #46ceff;
            font-size: 64px;
            transform: rotate(-2deg);
            filter: drop-shadow(0 10px 10px rgba(22, 96, 204, 0.22));
            z-index: 3;
        }

        .visual-import .doc {
            left: 47px;
            top: 26px;
            width: 36px;
            height: 46px;
            border-radius: 8px;
            background: linear-gradient(180deg, #ffffff 0%, #edf6ff 100%);
            color: #d4deee;
            box-shadow: 0 6px 16px rgba(255, 255, 255, 0.35);
            z-index: 2;
        }

        .visual-import .doc i {
            font-size: 26px;
            color: #d4deee;
        }

        .visual-import .arrow {
            right: 10px;
            top: 8px;
            color: #20e1ff;
            font-size: 56px;
            transform: rotate(2deg);
            filter: drop-shadow(0 8px 14px rgba(21, 184, 220, 0.34));
            z-index: 4;
        }

        /* Follow Ups */
        .theme-follow h4 {
            color: #dfad24;
        }

        .visual-follow {
            background: radial-gradient(circle at 32% 25%, #ffe874 0%, #ffc537 42%, #f79f16 78%, #f18d0a 100%);
            box-shadow:
                inset 0 8px 18px rgba(255, 255, 255, 0.22),
                0 22px 28px rgba(242, 162, 22, 0.24);
        }

        .visual-follow::before {
            content: "";
            position: absolute;
            inset: 16px;
            border-radius: 50%;
            background: radial-gradient(circle at 70% 38%, rgba(255, 255, 255, 0.24), transparent 48%);
            z-index: 0;
        }

        .visual-follow .mail {
            left: 23px;
            top: 50px;
            color: #f2ac10;
            font-size: 68px;
            filter: drop-shadow(0 10px 10px rgba(205, 125, 8, 0.18));
            z-index: 1;
        }

        .visual-follow .paper {
            left: 44px;
            top: 28px;
            width: 44px;
            height: 52px;
            border-radius: 10px;
            background: linear-gradient(180deg, #ffffff 0%, #fdf8ef 100%);
            box-shadow: 0 8px 18px rgba(255, 255, 255, 0.3);
            z-index: 2;
        }

        .visual-follow .paper i {
            font-size: 30px;
            color: #e8dcd0;
        }

        .visual-follow .bell {
            right: 10px;
            top: 56px;
            color: #ffbc11;
            font-size: 34px;
            z-index: 3;
            filter: drop-shadow(0 6px 10px rgba(199, 118, 8, 0.2));
        }

        .visual-follow .badge-dot {
            right: 1px;
            top: 26px;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: radial-gradient(circle at 35% 30%, #ff6464 0%, #f01717 72%, #c51010 100%);
            color: #fff;
            font-size: 17px;
            font-weight: 800;
            box-shadow: 0 8px 14px rgba(212, 22, 22, 0.24);
            z-index: 4;
        }

        /* Manage Campaigns */
        .theme-campaign h4 {
            color: #6c2a8d;
        }

        .visual-campaign {
            background: radial-gradient(circle at 50% 24%, #dfa7ff 0%, #be78ff 28%, #8a46d2 64%, #6a2bb8 100%);
            box-shadow:
                inset 0 8px 18px rgba(255, 255, 255, 0.2),
                0 22px 28px rgba(141, 76, 210, 0.22);
        }

        .visual-campaign .monitor {
            left: 20px;
            top: 34px;
            width: 94px;
            height: 68px;
            border-radius: 14px;
            background: linear-gradient(180deg, #3f2d92 0%, #5431a9 100%);
            box-shadow: 0 14px 20px rgba(79, 40, 145, 0.22);
            z-index: 1;
        }

        .visual-campaign .monitor::before {
            content: "";
            position: absolute;
            inset: 8px;
            border-radius: 10px;
            background: linear-gradient(180deg, #ffffff 0%, #f4e8ff 100%);
        }

        .visual-campaign .monitor::after {
            content: "";
            position: absolute;
            left: 39px;
            bottom: -9px;
            width: 16px;
            height: 12px;
            border-radius: 0 0 8px 8px;
            background: #6841bc;
        }

        .visual-campaign .chart {
            left: 36px;
            top: 52px;
            color: #7e3fd1;
            font-size: 34px;
            z-index: 3;
        }

        .visual-campaign .target {
            right: 1px;
            top: 60px;
            color: #e53dd2;
            font-size: 60px;
            filter: drop-shadow(0 8px 14px rgba(213, 42, 168, 0.22));
            z-index: 4;
        }

        .visual-campaign .cursor {
            right: 42px;
            top: 74px;
            color: #ff47d9;
            font-size: 20px;
            transform: rotate(-12deg);
            z-index: 5;
        }

        .visual-campaign .keyboard {
            left: 40px;
            bottom: 6px;
            color: #cfa7ff;
            font-size: 32px;
            z-index: 2;
        }

        /* U.S. Business */
        .theme-business h4 {
            color: #35415f;
        }

        .visual-business {
            width: 162px;
            height: 126px;
            border-radius: 22px;
            background:
                linear-gradient(180deg, #eefbff 0%, #d4f4ff 55%, #dff7ff 100%);
            box-shadow:
                inset 0 10px 16px rgba(255, 255, 255, 0.65),
                0 18px 28px rgba(124, 178, 220, 0.18);
            overflow: hidden;
        }

        .visual-business .cloud-left,
        .visual-business .cloud-right {
            position: absolute;
            background: rgba(255, 255, 255, 0.82);
            border-radius: 999px;
            z-index: 0;
        }

        .visual-business .cloud-left {
            width: 54px;
            height: 18px;
            left: 16px;
            top: 20px;
            box-shadow: 18px -6px 0 2px rgba(255, 255, 255, 0.82);
        }

        .visual-business .cloud-right {
            width: 46px;
            height: 16px;
            right: 18px;
            top: 28px;
            box-shadow: -16px -5px 0 2px rgba(255, 255, 255, 0.82);
        }

        .visual-business .tower {
            left: 42px;
            top: 14px;
            width: 62px;
            height: 90px;
            border-radius: 10px 10px 0 0;
            background: linear-gradient(180deg, #79e4ff 0%, #46aaf0 48%, #235ebd 100%);
            box-shadow: 0 10px 18px rgba(59, 123, 212, 0.2);
            z-index: 2;
        }

        .visual-business .tower::before {
            content: "";
            position: absolute;
            inset: 8px 7px;
            background:
                linear-gradient(90deg, rgba(255, 255, 255, 0.45) 0 1px, transparent 1px 100%),
                linear-gradient(rgba(255, 255, 255, 0.35) 0 1px, transparent 1px 100%);
            background-size: 12px 12px;
            border-radius: 6px 6px 0 0;
        }

        .visual-business .store {
            right: 12px;
            bottom: 18px;
            width: 68px;
            height: 46px;
            border-radius: 6px;
            background: linear-gradient(180deg, #ffffff 0%, #dfe9f8 100%);
            box-shadow: 0 10px 16px rgba(96, 129, 179, 0.16);
            z-index: 3;
        }

        .visual-business .store::before {
            content: "";
            position: absolute;
            left: 0;
            top: -10px;
            width: 100%;
            height: 12px;
            border-radius: 6px 6px 0 0;
            background: linear-gradient(90deg, #d89d5f, #f2c388, #d89d5f);
        }

        .visual-business .store::after {
            content: "";
            position: absolute;
            left: 10px;
            bottom: 8px;
            width: 16px;
            height: 20px;
            background: #62a0e0;
            border-radius: 3px 3px 0 0;
            box-shadow: 24px 0 0 0 #cce0f7, 38px 0 0 0 #cce0f7;
        }

        .visual-business .tree-left,
        .visual-business .tree-right {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: linear-gradient(180deg, #70d56b, #42a743);
            box-shadow: 0 8px 10px rgba(77, 164, 80, 0.16);
            z-index: 3;
        }

        .visual-business .tree-left {
            left: 14px;
            bottom: 20px;
        }

        .visual-business .tree-right {
            right: 6px;
            bottom: 18px;
        }

        .visual-business .road {
            left: 0;
            right: 0;
            bottom: 0;
            height: 20px;
            background: linear-gradient(180deg, #9cc7e6 0%, #86b5d7 100%);
            z-index: 1;
        }

        /* Table */
        .table-panel {
            margin-top: 34px;
            background: var(--card-bg);
            padding: 22px;
            border-radius: 22px;
            box-shadow: var(--shadow);
            border: 1px solid var(--line);
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
            margin: 0;
        }

        .table-header .badge {
            background-color: #eff4ff;
            color: var(--primary);
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 700;
            border: 1px solid #d7e3ff;
        }

        .table-wrap {
            overflow: auto;
            border-radius: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
        }

        table th,
        table td {
            padding: 14px 12px;
            text-align: left;
            font-size: 14px;
            border-bottom: 1px solid #eef1f6;
        }

        table th {
            background: #f7f9fd;
            font-weight: 700;
            color: #43506f;
        }

        table tbody tr:hover {
            background: #f9fbff;
        }

        .table-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
        }

        .table-link:hover {
            text-decoration: underline;
        }

        .icon-actions {
            display: flex;
            align-items: center;
            gap: 14px;
            color: #63708f;
            font-size: 15px;
        }

        .icon-actions i {
            cursor: pointer;
        }

        .icon-actions i:hover {
            color: var(--primary);
        }

        @media (max-width: 1200px) {
            .dashboard-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .quick-card {
                min-height: auto;
            }

            .table-header {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-header">
        <h2>Welcome, {{ auth()->user()->name }}!</h2>
        <p>Your dashboard overview and quick actions at a glance</p>
    </div>

    <div class="dashboard-grid">
        @foreach ($quickActions as $action)
            @php
                $title = strtolower(trim($action['title']));
                $theme = 'theme-import';
                $visual = 'import';

                if (str_contains($title, 'follow')) {
                    $theme = 'theme-follow';
                    $visual = 'follow';
                } elseif (str_contains($title, 'campaign')) {
                    $theme = 'theme-campaign';
                    $visual = 'campaign';
                } elseif (str_contains($title, 'business') || str_contains($title, 'u.s') || str_contains($title, 'us')) {
                    $theme = 'theme-business';
                    $visual = 'business';
                } else {
                    $theme = 'theme-import';
                    $visual = 'import';
                }
            @endphp

            <a href="{{ $action['url'] }}"
               class="quick-card {{ $theme }} {{ !$action['active'] ? 'disabled' : '' }}"
               @if (!$action['active']) onclick="return false;" @endif>
                <div class="quick-media">
                    @if ($visual === 'import')
                        <div class="action-visual visual-import" aria-hidden="true">
                            <span class="piece folder-back"><i class="fa-solid fa-folder"></i></span>
                            <span class="piece folder-front"><i class="fa-solid fa-folder-open"></i></span>
                            <span class="piece doc"><i class="fa-solid fa-file-lines"></i></span>
                            <span class="piece arrow"><i class="fa-solid fa-arrow-up"></i></span>
                        </div>
                    @elseif ($visual === 'follow')
                        <div class="action-visual visual-follow" aria-hidden="true">
                            <span class="piece mail"><i class="fa-solid fa-envelope"></i></span>
                            <span class="piece paper"><i class="fa-solid fa-file-lines"></i></span>
                            <span class="piece bell"><i class="fa-solid fa-bell"></i></span>
                            <span class="piece badge-dot">1</span>
                        </div>
                    @elseif ($visual === 'campaign')
                        <div class="action-visual visual-campaign" aria-hidden="true">
                            <span class="piece monitor"></span>
                            <span class="piece chart"><i class="fa-solid fa-chart-column"></i></span>
                            <span class="piece keyboard"><i class="fa-solid fa-keyboard"></i></span>
                            <span class="piece target"><i class="fa-solid fa-bullseye"></i></span>
                            <span class="piece cursor"><i class="fa-solid fa-location-arrow"></i></span>
                        </div>
                    @else
                        <div class="action-visual visual-business" aria-hidden="true">
                            <span class="piece cloud-left"></span>
                            <span class="piece cloud-right"></span>
                            <span class="piece tower"></span>
                            <span class="piece store"></span>
                            <span class="piece tree-left"></span>
                            <span class="piece tree-right"></span>
                            <span class="piece road"></span>
                        </div>
                    @endif
                </div>

                <h4>{{ $action['title'] }}</h4>
                <p>{{ $action['description'] }}</p>
            </a>
        @endforeach
    </div>

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
                    @foreach ($savedSearches as $search)
                        <tr>
                            <td>
                                <a href="{{ route('saved-lists.open', $search) }}" class="table-link">
                                    {{ $search['name'] }}
                                </a>
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