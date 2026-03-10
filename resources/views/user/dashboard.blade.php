@extends('layouts.user')

@section('title', 'User Dashboard')

@push('styles')
<style>
    .dashboard-grid{
        display:grid;
        grid-template-columns: repeat(5, 1fr);
        gap:24px;
        margin-top:10px;
    }

    .quick-card{
        text-align:center;
        padding:26px 18px 20px;
        border-radius:22px;
        background:rgba(255,255,255,.72);
        border:1px solid rgba(32, 57, 96, .08);
        transition:.35s ease;
        position:relative;
        overflow:hidden;
    }

    .quick-card::before{
        content:"";
        position:absolute;
        inset:auto -30px -40px auto;
        width:110px;
        height:110px;
        background:radial-gradient(circle, rgba(74,168,255,.14), transparent 68%);
        pointer-events:none;
    }

    .quick-card:hover{
        transform:translateY(-8px);
        box-shadow:0 20px 40px rgba(41, 72, 129, .15);
    }

    .quick-card.disabled{
        opacity:.88;
    }

    .quick-card .circle-icon{
        width:86px;
        height:86px;
        border-radius:50%;
        margin:0 auto 18px;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:34px;
        color:#fff;
        background:linear-gradient(135deg, #7cc8ff, #3f89ff);
        box-shadow:
            0 0 0 8px rgba(85, 164, 255, .10),
            0 18px 30px rgba(55, 125, 255, .22);
        animation:floaty 4s ease-in-out infinite;
    }

    .quick-card:nth-child(2) .circle-icon{
        background:linear-gradient(135deg, #ffd95d, #f7b500);
    }

    .quick-card:nth-child(3) .circle-icon{
        background:linear-gradient(135deg, #a9ecff, #65c9ff);
    }

    .quick-card:nth-child(4) .circle-icon{
        background:linear-gradient(135deg, #ff8b55, #ff6224);
    }

    .quick-card:nth-child(5) .circle-icon{
        background:linear-gradient(135deg, #c4d0da, #93a7b7);
    }

    .quick-card h4{
        font-size:22px;
        margin-bottom:10px;
        font-weight:800;
        letter-spacing:-0.03em;
    }

    .quick-card p{
        font-size:14px;
        color:#5d6d84;
        line-height:1.75;
        max-width:240px;
        margin:0 auto;
    }

    .section-panel{
        padding:30px;
        margin-bottom:26px;
    }

    .table-panel{
        padding:26px;
    }

    .table-header{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:14px;
        margin-bottom:18px;
        flex-wrap:wrap;
    }

    .table-header h3{
        font-size:28px;
        font-weight:800;
        display:flex;
        align-items:center;
        gap:12px;
    }

    .soft-badge{
        display:inline-flex;
        align-items:center;
        gap:8px;
        padding:10px 14px;
        border-radius:999px;
        background:rgba(74,168,255,.12);
        color:#2c6fe8;
        font-weight:800;
        font-size:13px;
    }

    .dashboard-hero{
        display:grid;
        grid-template-columns: 1.2fr .8fr;
        gap:24px;
        align-items:center;
    }

    .welcome-box{
        position:relative;
        z-index:2;
    }

    .hero-stats{
        display:grid;
        grid-template-columns:repeat(2, 1fr);
        gap:16px;
    }

    .stat-card{
        background:rgba(255,255,255,.72);
        border:1px solid rgba(32, 57, 96, .08);
        border-radius:20px;
        padding:20px;
        box-shadow:0 18px 35px rgba(24, 48, 93, .08);
    }

    .stat-card .label{
        font-size:13px;
        color:#70819b;
        margin-bottom:6px;
        font-weight:700;
    }

    .stat-card .value{
        font-size:30px;
        font-weight:800;
        letter-spacing:-0.04em;
    }

    .hero-actions{
        margin-top:18px;
        display:flex;
        gap:12px;
        flex-wrap:wrap;
    }

    .btn-primary-soft,
    .btn-dark-soft{
        display:inline-flex;
        align-items:center;
        gap:10px;
        border-radius:14px;
        padding:14px 18px;
        font-weight:800;
        font-size:14px;
        transition:.3s ease;
    }

    .btn-primary-soft{
        color:#fff;
        background:linear-gradient(135deg, #53b6ff, #2a74ff);
        box-shadow:0 14px 24px rgba(49, 120, 255, .28);
    }

    .btn-dark-soft{
        background:rgba(25, 34, 53, .08);
        color:#1f304d;
        border:1px solid rgba(25, 34, 53, .08);
    }

    .btn-primary-soft:hover,
    .btn-dark-soft:hover{
        transform:translateY(-3px);
    }

    @keyframes floaty{
        0%,100%{ transform:translateY(0); }
        50%{ transform:translateY(-8px); }
    }

    @media (max-width: 1200px){
        .dashboard-grid{
            grid-template-columns: repeat(3, 1fr);
        }

        .dashboard-hero{
            grid-template-columns:1fr;
        }
    }

    @media (max-width: 767px){
        .dashboard-grid{
            grid-template-columns:1fr;
        }

        .section-panel,
        .table-panel{
            padding:20px;
        }

        .hero-stats{
            grid-template-columns:1fr;
        }

        .quick-card h4{
            font-size:20px;
        }
    }
</style>
@endpush

@section('content')
    {{-- <div class="panel hero-card">
        <div class="dashboard-hero">
            <div class="welcome-box">
                <div class="soft-badge">
                    <i class="fa-solid fa-sparkles"></i>
                    Premium User Workspace
                </div>

                <h1 class="section-title" style="margin-top:18px;">
                    Welcome back, {{ auth()->user()->name }}
                </h1>

                <p class="section-subtitle">
                    Your user dashboard is now redesigned in a premium animated style.
                    Start with <strong>U.S. Business</strong> to open the business filter page
                    and search by company, executive name, state, city, address, ZIP and phone.
                </p>

                <div class="hero-actions">
                    <a href="{{ route('user.us-business.index') }}" class="btn-primary-soft">
                        <i class="fa-solid fa-building"></i>
                        Open U.S. Business
                    </a>

                    <a href="{{ route('user.dashboard') }}" class="btn-dark-soft">
                        <i class="fa-solid fa-gauge-high"></i>
                        Refresh Dashboard
                    </a>
                </div>
            </div>

            <div class="hero-stats">
                <div class="stat-card">
                    <div class="label">Saved Searches</div>
                    <div class="value">03</div>
                </div>

                <div class="stat-card">
                    <div class="label">Active Database</div>
                    <div class="value" style="font-size:22px;">U.S. Business</div>
                </div>

                <div class="stat-card">
                    <div class="label">Quick Search Mode</div>
                    <div class="value" style="font-size:22px;">Ready</div>
                </div>

                <div class="stat-card">
                    <div class="label">Account Role</div>
                    <div class="value" style="font-size:22px;">User</div>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="panel section-panel">
        <div class="table-header" style="margin-bottom:26px;">
            <div>
                <h3>
                    <i class="fa-solid fa-grid-2"></i>
                    Main Features
                </h3>
                <p class="section-subtitle" style="margin-top:8px;">
                    Styled close to your reference layout, but enhanced with animation,
                    glassmorphism, hover transitions and premium icon cards.
                </p>
            </div>
        </div>

        <div class="dashboard-grid">
            @foreach($quickActions as $action)
                <a href="{{ $action['url'] }}"
                   class="quick-card {{ !$action['active'] ? 'disabled' : '' }}"
                   @if(!$action['active']) onclick="return false;" @endif>
                    <div class="circle-icon">
                        <i class="fa-solid {{ $action['icon'] }}"></i>
                    </div>

                    <h4>{{ $action['title'] }}</h4>
                    <p>{{ $action['description'] }}</p>
                </a>
            @endforeach
        </div>
    </div>

    <div class="panel table-panel">
        <div class="table-header">
            <h3>
                <i class="fa-solid fa-bookmark"></i>
                Saved Searches ({{ count($savedSearches) }})
            </h3>

            <div class="soft-badge">
                <i class="fa-solid fa-database"></i>
                Business data ready
            </div>
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