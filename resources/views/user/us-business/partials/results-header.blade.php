@include('user.us-business.partials.results-assets')

@php
    $queryData = request()->query();
    $listRoute = route('user.us-business.results', $queryData);
    $insightRoute = route('user.us-business.insights', $queryData);
    $detailsRoute = route('user.us-business.details', $queryData);
    $mapRoute = route('user.us-business.map', $queryData);
@endphp

<div class="sg-shell">
    <div class="sg-breadcrumb-bar">
        <div class="sg-breadcrumbs">
            <span class="muted">data collector</span>
            <span>›</span>
            <span>U.S. Businesses</span>
            <span>›</span>
            <span>Search Results</span>
        </div>
        <div style="font-size:12px; color:rgba(255,255,255,.7); font-weight:700;">
            USER RESULT CENTER
        </div>
    </div>

    <div class="sg-filter-strip">
        <div class="sg-filter-left">
            <a href="{{ route('user.us-business.index') }}" class="sg-filter-btn">
                <i class="fa-solid fa-filter"></i> Filters
            </a>

            @foreach($activeFilters as $label => $value)
                @if(!empty($value))
                    <span class="sg-filter-chip">{{ $label }}: {{ $value }}</span>
                @endif
            @endforeach
        </div>

        <div class="sg-filter-right">
            <a href="{{ route('user.us-business.index') }}" class="sg-clear-link">CLEAR ALL</a>
        </div>
    </div>

    @if(session('success'))
        <div class="sg-alert success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="sg-alert error">{{ session('error') }}</div>
    @endif

    <div class="sg-top-toolbar">
        <div class="sg-top-toolbar-left">
            <div class="sg-count-box">
                <div class="num">{{ number_format($records->total()) }}</div>
                <div class="label">Records</div>
            </div>

            <div class="sg-action-group">
                <div class="sg-action-col">
                    <div class="sg-action-title">List Options</div>
                    <div class="sg-actions">
                        <button type="button" class="sg-action" data-modal-open="saveListModal">
                            <i class="fa-regular fa-floppy-disk"></i>
                            <span>Save</span>
                        </button>

                        <div class="sg-dropdown">
                            <button type="button" class="sg-action" data-export-toggle>
                                <i class="fa-regular fa-file-export"></i>
                                <span>Export</span>
                            </button>

                            <div class="sg-dropdown-menu">
                                <a href="{{ route('user.us-business.export.csv', $queryData) }}">
                                    <i class="fa-solid fa-file-csv"></i> Export CSV
                                </a>
                                <a href="{{ route('user.us-business.export.xlsx', $queryData) }}">
                                    <i class="fa-solid fa-file-excel"></i> Export XLSX
                                </a>
                            </div>
                        </div>

                        <div class="sg-tooltip-wrap">
                            <a href="javascript:void(0)" class="sg-action">
                                <i class="fa-solid fa-envelope-open-text"></i>
                                <span>Direct Mail</span>
                            </a>
                            <div class="sg-tooltip-bubble">
                                {{ number_format($directMailCount) }} Direct Mail Contacts
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sg-action-group">
                <div class="sg-action-col">
                    <div class="sg-action-title">Campaigns</div>
                    <div class="sg-actions">
                        <div class="sg-tooltip-wrap">
                            <a href="javascript:void(0)" class="sg-action">
                                <i class="fa-solid fa-at"></i>
                                <span>Email</span>
                            </a>
                            <div class="sg-tooltip-bubble">
                                {{ number_format($emailCount) }} Emails
                            </div>
                        </div>

                        <a href="javascript:void(0)" class="sg-action">
                            <i class="fa-solid fa-display"></i>
                            <span>Display Ads</span>
                        </a>

                        <a href="javascript:void(0)" class="sg-action">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                            <span>Intent Data</span>
                            <span class="new-badge">New</span>
                        </a>

                        <button type="button" class="sg-action" data-modal-open="columnsModal">
                            <i class="fa-solid fa-ellipsis"></i>
                            <span>More</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="sg-top-toolbar-right">
            <div class="sg-tab-switch">
                <a href="{{ $insightRoute }}" class="{{ $activeTab === 'insights' ? 'active' : '' }}">INSIGHTS</a>
                <a href="{{ $listRoute }}" class="{{ $activeTab === 'list' ? 'active' : '' }}">LIST</a>
                <a href="{{ $detailsRoute }}" class="{{ $activeTab === 'details' ? 'active' : '' }}">DETAILS</a>
                <a href="{{ $mapRoute }}" class="{{ $activeTab === 'map' ? 'active' : '' }}">MAP</a>
            </div>

            <button type="button" class="sg-column-btn" data-modal-open="columnsModal">
                <i class="fa-solid fa-ellipsis"></i>
            </button>
        </div>
    </div>