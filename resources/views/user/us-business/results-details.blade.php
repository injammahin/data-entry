@extends('layouts.user')

@section('shell_class', 'user-shell-fluid')
@section('title', 'Search Results - Details')

@section('content')
    @include('user.us-business.partials.results-header')

    <div class="sg-detail-grid" style="padding-top:18px;">
        @forelse($records as $record)
            @php
                $businessName = is_array($record->data_json) ? ($record->data_json['business_name'] ?? $record->data_json['Business Name'] ?? 'N/A') : 'N/A';
            @endphp

            <div class="sg-detail-card">
                <h4>{{ $businessName }}</h4>

                <div class="sg-detail-meta">
                    @foreach($visibleColumns as $column)
                        @php
                            $value = is_array($record->data_json) ? ($record->data_json[$column] ?? '') : '';
                            $label = $columnAliases[strtolower(trim(str_replace(['-', ' '], '_', $column)))] ?? strtoupper($column);
                        @endphp

                        <strong>{{ $label }}</strong>
                        <span>{!! nl2br(e($value)) !!}</span>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="sg-detail-card">
                No records found.
            </div>
        @endforelse
    </div>

    <div class="sg-footer" style="margin:0 16px 16px;">
        <div class="sg-mini-muted" style="display:flex; align-items:center; gap:8px;">
            <span>Page</span>

            <form method="GET" action="{{ url()->current() }}">
                @foreach(request()->except('page') as $key => $value)
                    @if(is_array($value))
                        @foreach($value as $single)
                            <input type="hidden" name="{{ $key }}[]" value="{{ $single }}">
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach

                <input
                    type="number"
                    name="page"
                    class="sg-page-box"
                    min="1"
                    max="{{ $records->lastPage() }}"
                    value="{{ $records->currentPage() }}"
                    onchange="this.form.submit()"
                >
            </form>

            <span>of {{ number_format($records->lastPage()) }}</span>
        </div>

        <div style="display:flex; align-items:center; gap:8px;">
            <a href="{{ $records->previousPageUrl() ?: '#' }}" @if($records->onFirstPage()) onclick="return false;" @endif>
                <button type="button" class="sg-pager-btn" {{ $records->onFirstPage() ? 'disabled' : '' }}>&lsaquo;</button>
            </a>

            <a href="{{ $records->nextPageUrl() ?: '#' }}" @if(!$records->hasMorePages()) onclick="return false;" @endif>
                <button type="button" class="sg-pager-btn" {{ !$records->hasMorePages() ? 'disabled' : '' }}>&rsaquo;</button>
            </a>
        </div>

        <div class="sg-mini-muted">
            Showing {{ number_format($records->firstItem() ?? 0) }}
            to {{ number_format($records->lastItem() ?? 0) }}
            of {{ number_format($records->total()) }}
        </div>
    </div>

    @include('user.us-business.partials.results-modals')
@endsection