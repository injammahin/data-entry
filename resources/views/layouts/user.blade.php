<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'User Panel')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet"> <!-- Correct CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    <style>
        :root {
            --bg: #eef3f9;
            --card: rgba(255, 255, 255, .88);
            --line: rgba(14, 30, 63, .08);
            --text: #1b2940;
            --muted: #6b7a90;
            --primary: #4aa8ff;
            --primary-dark: #1d74d8;
            --dark: #17191f;
            --dark-2: #232833;
            --success: #26b36b;
            --shadow: 0 20px 50px rgba(25, 43, 72, .12);
            --radius: 22px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(74, 167, 255, 0.637), transparent 28%),
                radial-gradient(circle at top left, rgba(74, 168, 255, .16), transparent 28%),
                6f6afeb (Add production build assets) radial-gradient(circle at right center, rgba(70, 217, 168, .12), transparent 24%),
                linear-gradient(180deg, #edf3fa 0%, #f7f9fc 100%);
            color: var(--text);
            min-height: 100vh;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .user-topbar {
            background: rgba(255, 255, 255, .78);
            color: #111827;
            padding: 18px 26px;
            position: sticky;
            top: 0;
            z-index: 1000;
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-bottom: 1px solid rgba(15, 23, 42, 0.06);
            box-shadow: 0 10px 35px rgba(15, 23, 42, .04);
            transition: background .35s ease, box-shadow .35s ease, padding .35s ease, border-color .35s ease;
        }

        .user-topbar.scrolled {
            background: rgba(255, 255, 255, .94);
            padding: 12px 26px;
            box-shadow: 0 16px 40px rgba(15, 23, 42, .10);
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
        }

        .topbar-inner {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            transition: all .35s ease;
        }

        .brand-wrap {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand-logo {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #59b8ff, #266dff);
            box-shadow: 0 8px 24px rgba(54, 125, 255, .22);
            font-size: 18px;
            color: #fff;
            transition: all .35s ease;
        }

        .user-topbar.scrolled .brand-logo {
            width: 40px;
            height: 40px;
            border-radius: 12px;
        }

        .brand-title {
            font-size: 30px;
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1;
            color: #111827;
            transition: all .35s ease;
        }

        .user-topbar.scrolled .brand-title {
            font-size: 26px;
        }

        .brand-title span {
            color: #2f80ed;
        }

        .brand-sub {
            font-size: 12px;
            color: #7b8797;
            margin-top: 3px;
            letter-spacing: .18em;
            text-transform: uppercase;
            transition: all .35s ease;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .top-link,
        .idea-btn {
            color: #111827;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .02em;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-radius: 999px;
            padding: 10px 14px;
            transition: .3s ease;
        }

        .top-link {
            background: rgba(255, 255, 255, .72);
            border: 1px solid rgba(15, 23, 42, .07);
            box-shadow: 0 10px 24px rgba(15, 23, 42, .04);
        }

        .top-link:hover,
        .idea-btn:hover {
            transform: translateY(-2px);
        }

        .top-link:hover {
            background: #f8fbff;
            color: #1d74d8;
            border-color: rgba(74, 168, 255, .24);
            box-shadow: 0 14px 28px rgba(53, 108, 208, .10);
        }

        .idea-btn {
            color: #fff;
            background: linear-gradient(135deg, #5cb9ff, #3c83ff);
            box-shadow: 0 12px 25px rgba(66, 137, 255, .22);
        }

        .user-info-chip {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .88);
            border: 1px solid rgba(15, 23, 42, .07);
            box-shadow: 0 10px 24px rgba(15, 23, 42, .04);
            transition: all .3s ease;
        }

        .user-info-chip:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(15, 23, 42, .08);
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: #fff;
            background: linear-gradient(135deg, #5ac2ff, #4775ff);
            box-shadow: 0 10px 25px rgba(82, 135, 255, .24);
        }

        .logout-btn {
            border: none;
            background: #fff;
            color: #111827;
            padding: 10px 14px;
            border-radius: 999px;
            cursor: pointer;
            font-weight: 700;
            border: 1px solid rgba(15, 23, 42, .08);
            box-shadow: 0 10px 24px rgba(15, 23, 42, .04);
            transition: .3s ease;
        }

        .logout-btn:hover {
            background: #fff5f5;
            color: #dc2626;
            border-color: rgba(220, 38, 38, .16);
            transform: translateY(-2px);
        }

        .user-shell {
            max-width: 1400px;
            margin: 0 auto;
            padding: 28px 24px 40px;
        }

        .notify-banner {
            margin-bottom: 24px;
            background: linear-gradient(90deg, #4f6779 0%, #57788e 100%);
            color: #fff;
            border-radius: 16px;
            padding: 14px 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            box-shadow: var(--shadow);
            animation: slideDown .7s ease;
        }

        .notify-banner .banner-text {
            font-weight: 600;
            font-size: 15px;
        }

        .notify-banner .banner-text a {
            color: #fff;
            text-decoration: underline;
            margin-left: 6px;
        }

        .panel {
            background: var(--card);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, .65);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .section-title {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.03em;
            margin-bottom: 8px;
        }

        .section-subtitle {
            color: var(--muted);
            font-size: 15px;
            line-height: 1.7;
        }

        .hero-card {
            padding: 28px;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
        }

        .hero-card::before {
            content: "";
            position: absolute;
            right: -80px;
            top: -80px;
            width: 240px;
            height: 240px;
            background: radial-gradient(circle, rgba(74, 168, 255, .25), transparent 65%);
            pointer-events: none;
        }

        .hero-card::after {
            content: "";
            position: absolute;
            left: -40px;
            bottom: -50px;
            width: 180px;
            height: 180px;
            background: radial-gradient(circle, rgba(38, 179, 107, .18), transparent 62%);
            pointer-events: none;
        }

        .page-content {
            animation: fadeUp .75s ease;
        }

        .mini-nav {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .mini-nav a {
            padding: 12px 16px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 14px;
            color: var(--muted);
            background: rgba(255, 255, 255, .62);
            border: 1px solid rgba(24, 46, 84, .08);
            transition: .3s ease;
        }

        .mini-nav a.active,
        .mini-nav a:hover {
            color: #fff;
            background: linear-gradient(135deg, #4faeff, #286fff);
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(54, 120, 255, .2);
        }

        .table-wrap {
            overflow: auto;
            border-radius: 18px;
            border: 1px solid var(--line);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 850px;
        }

        thead th {
            text-align: left;
            font-size: 12px;
            color: #64748b;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: 16px 18px;
            background: #f3f7fb;
            border-bottom: 1px solid var(--line);
        }

        tbody td {
            padding: 18px;
            border-bottom: 1px solid var(--line);
            font-size: 14px;
            color: #22314a;
        }

        tbody tr:hover {
            background: rgba(74, 168, 255, .045);
        }

        .table-link {
            color: #1e73d6;
            font-weight: 700;
        }

        .icon-actions {
            display: flex;
            align-items: center;
            gap: 14px;
            color: #9aa7b8;
            font-size: 18px;
        }

        .page-footer {
            margin-top: 26px;
            padding: 14px 10px 0;
            text-align: center;
            color: #8a98ad;
            font-size: 13px;
        }

        .user-shell.user-shell-fluid {
            max-width: 100%;
            padding: 0 0 24px;
        }

        .user-shell.user-shell-fluid .notify-banner,
        .user-shell.user-shell-fluid .mini-nav {
            margin-left: 16px;
            margin-right: 16px;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 991px) {
            .topbar-inner {
                flex-direction: column;
                align-items: flex-start;
            }

            .brand-title {
                font-size: 24px;
            }

            .user-topbar.scrolled .brand-title {
                font-size: 22px;
            }

            .user-shell {
                padding: 18px 14px 30px;
            }

            .hero-card {
                padding: 22px;
            }
        }

        @media (max-width: 767px) {
            .user-topbar {
                padding: 14px 16px;
            }

            .user-topbar.scrolled {
                padding: 10px 16px;
            }

            .notify-banner {
                flex-direction: column;
                align-items: flex-start;
            }

            .topbar-actions {
                width: 100%;
                justify-content: flex-start;
            }

            .mini-nav {
                flex-direction: column;
                align-items: stretch;
            }

            .mini-nav a {
                width: 100%;
            }

            .user-info-chip {
                width: 100%;
            }
        }

        .logo {
            height: 90px !important;
        }
    </style>

    @stack('styles')
</head>

<body>
    <header class="user-topbar" id="userTopbar">
        <div class="topbar-inner">
            <div class="brand-wrap">
                <div class="brand-logo grid justify-between">
                    <i class="fa-solid fa-database"></i>
                </div>

                <div>
                    <img src={{ asset('/img/logo.png') }} alt="LeadGen" class="h-16">


                </div>

                <div class="topbar-actions">
                    <div class="user-info-chip">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-size:13px; color:#8a98ad;">Signed in as</div>
                            <div style="font-size:14px; font-weight:800; color:#111827;">{{ auth()->user()->name }}
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
    </header>

    <main class="user-shell @yield('shell_class')">
        <div class="notify-banner">
            <div class="banner-text">
                New! Discover businesses with buying intent
                <a href="javascript:void(0)">Learn More</a>
            </div>
            <div>
                <i class="fa-solid fa-xmark"></i>
            </div>
        </div>

        <div class="mini-nav">
            <a href="{{ route('user.dashboard') }}" class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i>
                Dashboard
            </a>

            <a href="{{ route('user.us-business.index') }}"
                class="{{ request()->routeIs('user.us-business.*') ? 'active' : '' }}">
                <i class="fa-solid fa-building"></i>
                U.S. Business
            </a>
        </div>

        <div class="page-content">
            @yield('content')
        </div>

        <div class="page-footer">
            © {{ date('Y') }} Data Collector User Panel
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const topbar = document.getElementById('userTopbar');

            function handleHeaderScroll() {
                if (!topbar) return;

                if (window.scrollY > 20) {
                    topbar.classList.add('scrolled');
                } else {
                    topbar.classList.remove('scrolled');
                }
            }

            handleHeaderScroll();
            window.addEventListener('scroll', handleHeaderScroll, { passive: true });
        });
    </script>

    @stack('scripts')
</body>

</html>