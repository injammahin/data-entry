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

    <div class="sg-results-header">
        <div class="sg-results-top">
            <div class="sg-results-top-left">
                <h1 class="sg-page-title">Search Results</h1>

                @php
                    $activeFilterCount = collect($activeFilters)->filter(fn($value) => filled($value))->count();
                @endphp

                <div class="sg-filter-summary">
                    @if($activeFilterCount > 0)
                        <span>{{ $activeFilterCount }} filter{{ $activeFilterCount > 1 ? 's' : '' }} applied</span>
                    @else
                        <span>No active filters</span>
                    @endif
                </div>
            </div>

            <div class="sg-results-top-right">
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
                    <div class="num">{{ number_format($totalRecords ?? 0) }}</div>
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
                                    <i class="fa-solid fa-file-export"></i>
                                    <span>Export</span>
                                </button>

                                <div class="sg-dropdown-menu" data-export-menu>
                                    <a href="{{ route('user.us-business.export.csv', request()->query()) }}">
                                        <i class="fa-solid fa-file-csv"></i>
                                        CSV
                                    </a>
                                    <a href="{{ route('user.us-business.export.xlsx', request()->query()) }}">
                                        <i class="fa-solid fa-file-excel"></i>
                                        Excel
                                    </a>
                                </div>
                            </div>

                            <button type="button" class="sg-action" data-modal-open="columnsModal">
                                <i class="fa-solid fa-table-columns"></i>
                                <span>Columns</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sg-top-toolbar-right">
                <form method="GET" action="{{ url()->current() }}" class="sg-per-page-form">
                    @foreach(request()->except('per_page', 'page') as $key => $value)
                        @if(is_array($value))
                            @foreach($value as $subValue)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $subValue }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach

                    <label for="per_page">Show</label>
                    <select name="per_page" id="per_page" onchange="this.form.submit()">
                        <option value="12" {{ (int) $perPage === 12 ? 'selected' : '' }}>12</option>
                        <option value="30" {{ (int) $perPage === 30 ? 'selected' : '' }}>30</option>
                        <option value="50" {{ (int) $perPage === 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ (int) $perPage === 100 ? 'selected' : '' }}>100</option>
                    </select>
                </form>
            </div>
        </div>
    </div>