<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Data Collector</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root {
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-active: linear-gradient(135deg, #2563eb, #1d4ed8);
            --sidebar-text: #cbd5e1;
            --sidebar-muted: #94a3b8;
            --topbar-bg: #ffffff;
            --page-bg: #f1f5f9;
            --card-bg: #ffffff;
            --border-color: #e2e8f0;
            --heading-color: #0f172a;
            --text-color: #334155;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--page-bg);
            color: var(--text-color);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .admin-sidebar {
            width: 270px;
            background: var(--sidebar-bg);
            color: white;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            box-shadow: 8px 0 24px rgba(15, 23, 42, 0.08);
            z-index: 1030;
        }

        .sidebar-brand {
            padding: 24px 22px 18px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .brand-logo {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            font-size: 20px;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.35);
        }

        .brand-title {
            font-size: 18px;
            font-weight: 700;
            line-height: 1.2;
            margin: 0;
            color: #fff;
        }

        .brand-subtitle {
            font-size: 12px;
            color: var(--sidebar-muted);
            margin: 4px 0 0;
        }

        .sidebar-section-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--sidebar-muted);
            padding: 22px 22px 10px;
            font-weight: 700;
        }

        .sidebar-nav {
            padding: 0 14px 18px;
        }

        .sidebar-nav .nav-link {
            color: var(--sidebar-text);
            border-radius: 14px;
            padding: 12px 14px;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s ease;
            margin-bottom: 6px;
        }

        .sidebar-nav .nav-link i {
            font-size: 17px;
            width: 20px;
            text-align: center;
        }

        .sidebar-nav .nav-link:hover {
            background: var(--sidebar-hover);
            color: #fff;
            transform: translateX(2px);
        }

        .sidebar-nav .nav-link.active {
            background: var(--sidebar-active);
            color: #fff;
            box-shadow: 0 10px 22px rgba(37, 99, 235, 0.25);
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 18px 18px 22px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-user-box {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 16px;
            padding: 14px;
        }

        .sidebar-user-box .avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
        }

        .sidebar-user-box .name {
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 2px;
        }

        .sidebar-user-box .role {
            font-size: 12px;
            color: var(--sidebar-muted);
        }

        .admin-main {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .admin-topbar {
            background: var(--topbar-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 18px 24px;
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .topbar-title {
            font-size: 24px;
            font-weight: 800;
            color: var(--heading-color);
            margin: 0;
        }

        .topbar-subtitle {
            font-size: 13px;
            color: #64748b;
            margin-top: 4px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .topbar-icon-btn {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            background: #fff;
            color: #475569;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: 0.2s ease;
        }

        .topbar-icon-btn:hover {
            background: #f8fafc;
            color: #0f172a;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 14px;
            background: #fff;
        }

        .topbar-user .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            font-weight: 700;
        }

        .topbar-user .name {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 1px;
        }

        .topbar-user .meta {
            font-size: 12px;
            color: #64748b;
        }

        .admin-content {
            padding: 24px;
        }

        .page-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(15, 23, 42, 0.04);
            overflow: hidden;
        }

        .custom-alert {
            border: none;
            border-radius: 16px;
            padding: 14px 16px;
            font-weight: 600;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.04);
        }

        .admin-mobile-top {
            display: none;
        }

        @media (max-width: 991.98px) {
            .admin-sidebar {
                position: fixed;
                left: -270px;
                transition: left 0.25s ease;
            }

            .admin-sidebar.show {
                left: 0;
            }

            .admin-mobile-top {
                display: flex;
                align-items: center;
                justify-content: space-between;
                background: #fff;
                border-bottom: 1px solid var(--border-color);
                padding: 14px 16px;
            }

            .admin-topbar {
                position: static;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    @php
        $user = auth()->user();
        $userInitial = $user ? strtoupper(substr($user->name, 0, 1)) : 'A';
        $pageTitle = trim($__env->yieldContent('title', 'Dashboard'));
    @endphp

    <div class="admin-mobile-top">
        <button class="btn btn-outline-secondary" type="button" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>
        <div class="fw-bold">Data Collector</div>
        <div></div>
    </div>

    <div class="admin-wrapper">
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-brand">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none d-flex align-items-center gap-3">
                    <div class="brand-logo">
                        <i class="bi bi-database-fill"></i>
                    </div>
                    <div>
                        <h1 class="brand-title">Data Collector</h1>
                        <p class="brand-subtitle">Admin Management Panel</p>
                    </div>
                </a>
            </div>

            <div class="sidebar-section-title">Main Navigation</div>

            <nav class="sidebar-nav">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}"
                           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-grid-1x2-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.states.index') }}"
                           class="nav-link {{ request()->routeIs('admin.states.*') ? 'active' : '' }}">
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>States</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.imports.index') }}"
                           class="nav-link {{ request()->routeIs('admin.imports.*') ? 'active' : '' }}">
                            <i class="bi bi-upload"></i>
                            <span>Imports</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.records.index') }}"
                           class="nav-link {{ request()->routeIs('admin.records.*') ? 'active' : '' }}">
                            <i class="bi bi-table"></i>
                            <span>Records</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="sidebar-footer">
                <div class="sidebar-user-box">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="avatar">{{ $userInitial }}</div>
                        <div>
                            <div class="name">{{ $user->name ?? 'Admin User' }}</div>
                            <div class="role">Administrator</div>
                        </div>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-light w-100 rounded-3">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <main class="admin-main">
            <div class="admin-topbar">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h2 class="topbar-title">{{ $pageTitle }}</h2>
                        <div class="topbar-subtitle">
                            Welcome back, {{ $user->name ?? 'Admin' }}. Manage your data collection system here.
                        </div>
                    </div>

                    <div class="topbar-right">
                        <button type="button" class="topbar-icon-btn d-lg-none" onclick="toggleSidebar()">
                            <i class="bi bi-list"></i>
                        </button>

                        <button type="button" class="topbar-icon-btn">
                            <i class="bi bi-bell"></i>
                        </button>

                        <button type="button" class="topbar-icon-btn">
                            <i class="bi bi-gear"></i>
                        </button>

                        <div class="topbar-user">
                            <div class="avatar">{{ $userInitial }}</div>
                            <div>
                                <div class="name">{{ $user->name ?? 'Admin User' }}</div>
                                <div class="meta">Administrator</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-content">
                @if(session('success'))
                    <div class="alert alert-success custom-alert mb-4">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger custom-alert mb-4">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('adminSidebar').classList.toggle('show');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>