@extends('layouts.user')

@section('title', 'U.S. Business Search')

@push('styles')
    <style>
        .search-type-wrap {
            padding: 24px 28px;
            margin-bottom: 24px;
        }

        .search-type-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 18px;
            align-items: start;
        }

        .type-card {
            text-align: center;
            padding: 18px 12px;
            border-radius: 22px;
            background: rgba(255, 255, 255, .72);
            border: 1px solid rgba(32, 57, 96, .08);
            transition: .35s ease;
            display: block;
        }

        .type-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 18px 34px rgba(38, 63, 104, .14);
        }

        .type-card.active {
            border-color: rgba(54, 119, 255, .24);
            box-shadow: 0 20px 38px rgba(53, 117, 255, .14);
        }

        .type-circle {
            width: 84px;
            height: 84px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
            font-size: 32px;
            color: #fff;
            box-shadow:
                0 0 0 8px rgba(85, 164, 255, .10),
                0 18px 30px rgba(55, 125, 255, .22);
        }

        .type-card.cancel .type-circle {
            background: linear-gradient(135deg, #d9d9d9, #b9bcc1);
            color: #fff;
        }

        .type-card.business .type-circle {
            background: linear-gradient(135deg, #ededed, #8392ab);
        }

        .type-card.new-business .type-circle {
            background: linear-gradient(135deg, #75b3ff, #3e76ff);
        }

        .type-card.consumer .type-circle {
            background: linear-gradient(135deg, #98ead6, #5fd6a7);
        }

        .type-card.movers .type-circle {
            background: linear-gradient(135deg, #d9edf9, #9bcaf3);
            color: #1d578a;
        }

        .type-card h4 {
            font-size: 15px;
            font-weight: 800;
            line-height: 1.45;
        }

        .search-layout {
            display: grid;
            grid-template-columns: 1.3fr .7fr;
            gap: 24px;
            align-items: start;
        }

        .search-panel {
            padding: 30px;
            position: relative;
            overflow: hidden;
        }

        .search-panel::before {
            content: "";
            position: absolute;
            top: -90px;
            right: -80px;
            width: 240px;
            height: 240px;
            background: radial-gradient(circle, rgba(74, 168, 255, .15), transparent 65%);
            pointer-events: none;
        }

        .search-panel h2 {
            font-size: 44px;
            font-weight: 800;
            letter-spacing: -0.04em;
            margin-bottom: 10px;
        }

        .search-panel p {
            color: #64748b;
            font-size: 15px;
            line-height: 1.75;
            margin-bottom: 26px;
        }

        .filters-form {
            border-top: 1px solid rgba(20, 40, 76, .10);
            padding-top: 26px;
        }

        .field-row {
            display: grid;
            grid-template-columns: 240px 1fr;
            gap: 18px;
            align-items: center;
            margin-bottom: 18px;
        }

        .field-label {
            font-weight: 800;
            font-size: 15px;
            color: #1f2e46;
        }

        .form-control,
        .form-select {
            width: 100%;
            height: 52px;
            border-radius: 16px;
            border: 1px solid rgba(24, 46, 84, .12);
            background: #fff;
            padding: 0 16px;
            font-size: 15px;
            color: #24354f;
            outline: none;
            transition: .3s ease;
            box-shadow: 0 8px 20px rgba(18, 42, 77, .04);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #5aaeff;
            box-shadow: 0 0 0 4px rgba(90, 174, 255, .12);
        }

        .search-actions {
            padding-top: 10px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            justify-content: flex-start;
            margin-left: 258px;
        }

        .btn-search,
        .btn-clear {
            border: none;
            border-radius: 15px;
            padding: 14px 18px;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
            transition: .3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-search {
            background: linear-gradient(135deg, #53b6ff, #2a74ff);
            color: #fff;
            box-shadow: 0 16px 26px rgba(49, 120, 255, .26);
        }

        .btn-clear {
            background: rgba(24, 38, 64, .08);
            color: #253650;
        }

        .btn-search:hover,
        .btn-clear:hover {
            transform: translateY(-3px);
        }

        .side-panel {
            padding: 26px;
        }

        .tips-card {
            padding: 24px;
            border-radius: 22px;
            background: linear-gradient(180deg, rgba(82, 170, 255, .12), rgba(255, 255, 255, .82));
            border: 1px solid rgba(41, 88, 160, .08);
            margin-bottom: 18px;
        }

        .tips-card h3 {
            font-size: 22px;
            margin-bottom: 10px;
            font-weight: 800;
        }

        .tips-card p {
            font-size: 14px;
            line-height: 1.8;
            color: #627389;
            margin-bottom: 14px;
        }

        .tip-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 0;
            border-top: 1px dashed rgba(28, 52, 92, .12);
        }

        .tip-item:first-of-type {
            border-top: none;
            padding-top: 0;
        }

        .tip-icon {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: linear-gradient(135deg, #6bc1ff, #3278ff);
            color: #fff;
            box-shadow: 0 10px 18px rgba(50, 120, 255, .22);
        }

        .tip-text strong {
            display: block;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .tip-text span {
            color: #6b7a90;
            font-size: 13px;
            line-height: 1.7;
        }

        .alert-soft {
            margin-bottom: 18px;
            padding: 14px 16px;
            border-radius: 14px;
            background: rgba(255, 93, 93, .08);
            color: #a43a3a;
            border: 1px solid rgba(255, 93, 93, .18);
            font-weight: 600;
        }

        @media (max-width: 1200px) {
            .search-type-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .search-layout {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .search-type-grid {
                grid-template-columns: 1fr;
            }

            .field-row {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .search-actions {
                margin-left: 0;
            }

            .search-panel {
                padding: 22px;
            }

            .search-panel h2 {
                font-size: 30px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="panel search-type-wrap">
        <div class="search-type-grid">
            <!-- Cancel Search Card -->
            <a href="{{ route('user.dashboard') }}" class="type-card cancel">
                <div class="type-circle cancel-icon">
                    <i class="fa-solid fa-xmark"></i>
                </div>
                <h4>Cancel Search</h4>
            </a>

            <!-- U.S. Businesses Card -->
            <a href="{{ route('user.us-business.index') }}" class="type-card business active">
                <div class="type-circle business-icon">
                    <i class="fa-solid fa-building"></i>
                </div>
                <h4>U.S. Businesses</h4>
            </a>
        </div>
    </div>

    <style>
        .search-type-wrap {
            padding: 2rem;
            background: linear-gradient(to right, #f7f8f9, #fff);
            border-radius: 15px;
        }

        .search-type-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
            justify-items: center;
            margin: 0;
        }

        .type-card {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
            text-align: center;
            width: 100%;
            max-width: 280px;
            cursor: pointer;
        }

        .type-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.2);
        }

        .type-card.active {
            background: #084b90;
            /* Use the custom color */
            color: white;
            box-shadow: 0 8px 15px rgba(8, 75, 144, 0.3);
            /* Lighter shadow with the active color */
        }

        .type-card.cancel {
            background: #084b90;
        }

        .type-card.business {
            background: #044754f7;
        }

        .type-circle {
            width: 70px;
            height: 70px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            margin-bottom: 1rem;
            transition: background-color 0.3s ease, transform 0.3s ease;
            background-color: white;
            /* Circle background color */
        }

        .type-card:hover .type-circle {
            transform: rotate(15deg);
        }

        .cancel-icon {
            color: #d9534f;
        }

        .business-icon {
            color: #084b90;
            /* Icon color for business card */
        }

        .type-card h4 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #ececec;
        }

        /* Animations */
        @keyframes bounce {
            0% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }

            100% {
                transform: translateY(0);
            }
        }

        .type-card:hover {
            animation: bounce 0.5s ease-out;
        }
    </style>

    <div class="search-layout">
        <div class="panel search-panel">
            <h2>Find a Business</h2>
            <p>
                Fill out one or more of the following search fields.
            </p>

            @if(session('error'))
                <div class="alert-soft">
                    {{ session('error') }}
                </div>
            @endif

            <form method="GET" action="{{ route('user.us-business.results') }}" class="filters-form">
                <input type="hidden" name="per_page" value="30">

                <div class="field-row">
                    <label class="field-label">Business Name</label>
                    <input type="text" name="business_name" class="form-control" placeholder="Enter a company name">
                </div>

                <div class="field-row">
                    <label class="field-label">Executive First Name</label>
                    <input type="text" name="executive_first_name" class="form-control" placeholder="First Name">
                </div>

                <div class="field-row">
                    <label class="field-label">Executive Last Name</label>
                    <input type="text" name="executive_last_name" class="form-control" placeholder="Last Name">
                </div>

                <div class="field-row">
                    <label class="field-label">State</label>
                    <select name="state_id" class="form-select">
                        <option value="">All</option>
                        @foreach($states as $state)
                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field-row">
                    <label class="field-label">City</label>
                    <input type="text" name="city" class="form-control" placeholder="City">
                </div>

                <div class="field-row">
                    <label class="field-label">Address</label>
                    <input type="text" name="address" class="form-control" placeholder="Number and/or street name">
                </div>

                <div class="field-row">
                    <label class="field-label">ZIP Code</label>
                    <input type="text" name="zip_code" class="form-control" placeholder="Enter a ZIP Code">
                </div>

                <div class="field-row">
                    <label class="field-label">Phone Number</label>
                    <input type="text" name="phone_number" class="form-control" placeholder="Enter a Phone Number">
                </div>

                <div class="search-actions">
                    <button type="submit" class="btn-search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        Search Business
                    </button>

                    <a href="{{ route('user.us-business.index') }}" class="btn-clear">
                        <i class="fa-solid fa-rotate-left"></i>
                        Clear Filters
                    </a>
                </div>
            </form>
        </div>

        <div class="side-panel">
            <div class="panel tips-card">
                <h3>
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    Search Tips
                </h3>
                <p>
                    Use one or more fields to narrow down results. State will filter by exact state relation,
                    and the other fields will dynamically search inside imported row data.
                </p>

                <div class="tip-item">
                    <div class="tip-icon">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <div class="tip-text">
                        <strong>Business name</strong>
                        <span>Best for finding a specific company or organization.</span>
                    </div>
                </div>

                <div class="tip-item">
                    <div class="tip-icon">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div class="tip-text">
                        <strong>State and city</strong>
                        <span>Use location to narrow down large record sets quickly.</span>
                    </div>
                </div>

                <div class="tip-item">
                    <div class="tip-icon">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <div class="tip-text">
                        <strong>Executive fields</strong>
                        <span>Search by owner or executive name when you know the person.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection