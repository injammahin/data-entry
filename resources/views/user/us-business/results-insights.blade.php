@extends('layouts.user')

@section('shell_class', 'user-shell-fluid')
@section('title', 'Search Results - Insights')

@section('content')
    @include('user.us-business.partials.results-header')

    <div class="sg-stats-grid">
        <div class="sg-stat-card">
            <div class="k">{{ number_format($insights['total_results']) }}</div>
            <div class="t">Total Results</div>
        </div>

        <div class="sg-stat-card">
            <div class="k">{{ number_format($insights['email_count']) }}</div>
            <div class="t">Available Emails</div>
        </div>

        <div class="sg-stat-card">
            <div class="k">{{ number_format($insights['direct_mail_count']) }}</div>
            <div class="t">Direct Mail Ready</div>
        </div>

        <div class="sg-stat-card">
            <div class="k">{{ number_format($insights['state_count']) }}</div>
            <div class="t">States Covered</div>
        </div>
    </div>

    <div class="sg-insight-grid">
        <div class="sg-panel">
            <div class="sg-panel-head">Top Cities</div>
            <div class="sg-panel-body">
                <ul class="sg-rank-list">
                    @forelse($insights['top_cities'] as $row)
                        <li>
                            <span>{{ $row->label }}</span>
                            <strong>{{ number_format($row->total) }}</strong>
                        </li>
                    @empty
                        <li><span>No city data found.</span></li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="sg-panel">
            <div class="sg-panel-head">Top SIC Descriptions</div>
            <div class="sg-panel-body">
                <ul class="sg-rank-list">
                    @forelse($insights['top_sic_descriptions'] as $row)
                        <li>
                            <span>{{ $row->label }}</span>
                            <strong>{{ number_format($row->total) }}</strong>
                        </li>
                    @empty
                        <li><span>No SIC data found.</span></li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="sg-panel">
            <div class="sg-panel-head">Top Executive Titles</div>
            <div class="sg-panel-body">
                <ul class="sg-rank-list">
                    @forelse($insights['top_titles'] as $row)
                        <li>
                            <span>{{ $row->label }}</span>
                            <strong>{{ number_format($row->total) }}</strong>
                        </li>
                    @empty
                        <li><span>No title data found.</span></li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="sg-panel">
            <div class="sg-panel-head">Current Filter Summary</div>
            <div class="sg-panel-body">
                <ul class="sg-rank-list">
                    @foreach($activeFilters as $label => $value)
                        @if(!empty($value))
                            <li>
                                <span>{{ $label }}</span>
                                <strong>{{ $value }}</strong>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    @include('user.us-business.partials.results-modals')
@endsection