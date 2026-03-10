<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'User Panel')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

    <style>
        :root{
            --bg: #eef3f9;
            --card: rgba(255,255,255,.88);
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

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:'Inter', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(74,168,255,.16), transparent 28%),
                radial-gradient(circle at right center, rgba(70, 217, 168, .12), transparent 24%),
                linear-gradient(180deg, #edf3fa 0%, #f7f9fc 100%);
            color:var(--text);
            min-height:100vh;
            overflow-x:hidden;
        }

        a{
            text-decoration:none;
            color:inherit;
        }

        .user-topbar{
            background: linear-gradient(90deg, #17171d 0%, #232731 100%);
            color:#fff;
            padding:18px 26px;
            position:sticky;
            top:0;
            z-index:1000;
            box-shadow:0 10px 35px rgba(0,0,0,.14);
        }

        .topbar-inner{
            max-width:1400px;
            margin:0 auto;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:20px;
        }

        .brand-wrap{
            display:flex;
            align-items:center;
            gap:14px;
        }

        .brand-logo{
            width:44px;
            height:44px;
            border-radius:14px;
            display:flex;
            align-items:center;
            justify-content:center;
            background:linear-gradient(135deg, #59b8ff, #266dff);
            box-shadow:0 8px 24px rgba(54, 125, 255, .35);
            font-size:18px;
        }

        .brand-title{
            font-size:30px;
            font-weight:800;
            letter-spacing:-0.5px;
            line-height:1;
        }

        .brand-title span{
            color:#62c1ff;
        }

        .brand-sub{
            font-size:12px;
            color:rgba(255,255,255,.65);
            margin-top:3px;
            letter-spacing:.18em;
            text-transform:uppercase;
        }

        .topbar-actions{
            display:flex;
            align-items:center;
            gap:12px;
            flex-wrap:wrap;
        }

        .top-link,
        .idea-btn{
            color:#fff;
            font-size:13px;
            font-weight:700;
            letter-spacing:.02em;
            display:inline-flex;
            align-items:center;
            gap:8px;
            border-radius:999px;
            padding:10px 14px;
            transition:.3s ease;
        }

        .top-link:hover,
        .idea-btn:hover{
            transform:translateY(-2px);
        }

        .idea-btn{
            background:linear-gradient(135deg, #5cb9ff, #3c83ff);
            box-shadow:0 12px 25px rgba(66, 137, 255, .28);
        }

        .user-shell{
            max-width:1400px;
            margin:0 auto;
            padding:28px 24px 40px;
        }

        .notify-banner{
            margin-bottom:24px;
            background:linear-gradient(90deg, #4f6779 0%, #57788e 100%);
            color:#fff;
            border-radius:16px;
            padding:14px 18px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:16px;
            box-shadow:var(--shadow);
            animation:slideDown .7s ease;
        }

        .notify-banner .banner-text{
            font-weight:600;
            font-size:15px;
        }

        .notify-banner .banner-text a{
            color:#fff;
            text-decoration:underline;
            margin-left:6px;
        }

        .panel{
            background:var(--card);
            backdrop-filter: blur(14px);
            border:1px solid rgba(255,255,255,.65);
            border-radius:var(--radius);
            box-shadow:var(--shadow);
        }

        .section-title{
            font-size:28px;
            font-weight:800;
            letter-spacing:-0.03em;
            margin-bottom:8px;
        }

        .section-subtitle{
            color:var(--muted);
            font-size:15px;
            line-height:1.7;
        }

        .hero-card{
            padding:28px;
            margin-bottom:24px;
            position:relative;
            overflow:hidden;
        }

        .hero-card::before{
            content:"";
            position:absolute;
            right:-80px;
            top:-80px;
            width:240px;
            height:240px;
            background:radial-gradient(circle, rgba(74,168,255,.25), transparent 65%);
            pointer-events:none;
        }

        .hero-card::after{
            content:"";
            position:absolute;
            left:-40px;
            bottom:-50px;
            width:180px;
            height:180px;
            background:radial-gradient(circle, rgba(38,179,107,.18), transparent 62%);
            pointer-events:none;
        }

        .page-content{
            animation:fadeUp .75s ease;
        }

        .mini-nav{
            display:flex;
            align-items:center;
            gap:12px;
            flex-wrap:wrap;
            margin-bottom:20px;
        }

        .mini-nav a{
            padding:12px 16px;
            border-radius:14px;
            font-weight:700;
            font-size:14px;
            color:var(--muted);
            background:rgba(255,255,255,.62);
            border:1px solid rgba(24, 46, 84, .08);
            transition:.3s ease;
        }

        .mini-nav a.active,
        .mini-nav a:hover{
            color:#fff;
            background:linear-gradient(135deg, #4faeff, #286fff);
            transform:translateY(-2px);
            box-shadow:0 12px 25px rgba(54, 120, 255, .2);
        }

        .user-info-chip{
            display:flex;
            align-items:center;
            gap:12px;
            padding:10px 14px;
            border-radius:999px;
            background:rgba(255,255,255,.1);
            border:1px solid rgba(255,255,255,.12);
        }

        .user-avatar{
            width:38px;
            height:38px;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:800;
            background:linear-gradient(135deg, #5ac2ff, #4775ff);
            box-shadow:0 10px 25px rgba(82,135,255,.35);
        }

        .logout-btn{
            border:none;
            background:rgba(255,255,255,.1);
            color:#fff;
            padding:10px 14px;
            border-radius:999px;
            cursor:pointer;
            font-weight:700;
            transition:.3s ease;
        }

        .logout-btn:hover{
            background:rgba(255,255,255,.18);
        }

        .table-wrap{
            overflow:auto;
            border-radius:18px;
            border:1px solid var(--line);
        }

        table{
            width:100%;
            border-collapse:collapse;
            min-width:850px;
        }

        thead th{
            text-align:left;
            font-size:12px;
            color:#64748b;
            letter-spacing:.08em;
            text-transform:uppercase;
            padding:16px 18px;
            background:#f3f7fb;
            border-bottom:1px solid var(--line);
        }

        tbody td{
            padding:18px;
            border-bottom:1px solid var(--line);
            font-size:14px;
            color:#22314a;
        }

        tbody tr:hover{
            background:rgba(74,168,255,.045);
        }

        .table-link{
            color:#1e73d6;
            font-weight:700;
        }

        .icon-actions{
            display:flex;
            align-items:center;
            gap:14px;
            color:#9aa7b8;
            font-size:18px;
        }

        .page-footer{
            margin-top:26px;
            padding:14px 10px 0;
            text-align:center;
            color:#8a98ad;
            font-size:13px;
        }
        .user-shell.user-shell-fluid{
        max-width:100%;
        padding:0 0 24px;
    }

    .user-shell.user-shell-fluid .notify-banner,
    .user-shell.user-shell-fluid .mini-nav{
        margin-left:16px;
        margin-right:16px;
    }

        @keyframes fadeUp{
            from{
                opacity:0;
                transform:translateY(18px);
            }
            to{
                opacity:1;
                transform:translateY(0);
            }
        }

        @keyframes slideDown{
            from{
                opacity:0;
                transform:translateY(-18px);
            }
            to{
                opacity:1;
                transform:translateY(0);
            }
        }

        @media (max-width: 991px){
            .topbar-inner{
                flex-direction:column;
                align-items:flex-start;
            }

            .brand-title{
                font-size:24px;
            }

            .user-shell{
                padding:18px 14px 30px;
            }

            .hero-card{
                padding:22px;
            }
        }

        @media (max-width: 767px){
            .notify-banner{
                flex-direction:column;
                align-items:flex-start;
            }

            .topbar-actions{
                width:100%;
                justify-content:flex-start;
            }

            .mini-nav{
                flex-direction:column;
                align-items:stretch;
            }

            .mini-nav a{
                width:100%;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <header class="user-topbar">
        <div class="topbar-inner">
            <div class="brand-wrap">
                <div class="brand-logo">
                    <i class="fa-solid fa-database"></i>
                </div>

                <div>
                    <div class="brand-title">data <span>collector</span></div>
                    <div class="brand-sub">user business panel</div>
                </div>
            </div>

            <div class="topbar-actions">
                <a href="javascript:void(0)" class="idea-btn">
                    <i class="fa-regular fa-lightbulb"></i>
                    Have an Idea?
                </a>

                <a href="javascript:void(0)" class="top-link">
                    <i class="fa-solid fa-comments"></i>
                    Live Chat
                </a>

                <a href="javascript:void(0)" class="top-link">
                    <i class="fa-solid fa-circle-info"></i>
                    Help
                </a>

                <div class="user-info-chip">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-size:13px; opacity:.72;">Signed in as</div>
                        <div style="font-size:14px; font-weight:800;">{{ auth()->user()->name }}</div>
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

            <a href="{{ route('user.us-business.index') }}" class="{{ request()->routeIs('user.us-business.*') ? 'active' : '' }}">
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

    @stack('scripts')
</body>
</html>