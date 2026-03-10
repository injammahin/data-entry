@extends('layouts.user')

@section('shell_class', 'user-shell-fluid')
@section('title', 'Search Results - Map')

@section('content')
    @include('user.us-business.partials.results-header')

    <div class="sg-map-grid" style="padding-top:18px;">
        @forelse($records as $record)
            @php
                $data = is_array($record->data_json) ? $record->data_json : [];
                $businessName = $data['business_name'] ?? $data['Business Name'] ?? 'N/A';
                $address = $data['address'] ?? $data['Address'] ?? '';
                $city = $data['city'] ?? $data['City'] ?? '';
                $state = $data['state'] ?? $data['State'] ?? ($record->state->name ?? '');
                $zip = $data['zip'] ?? $data['Zip'] ?? $data['ZIP'] ?? $data['zip_code'] ?? '';
                $phone = $data['phone'] ?? $data['Phone'] ?? $data['PHONE'] ?? $data['phone_number'] ?? '';
                $mapQuery = trim($address . ', ' . $city . ', ' . $state . ' ' . $zip);
            @endphp

            <div class="sg-map-card">
                <h4>{{ $businessName }}</h4>

                <div class="sg-map-meta">
                    <strong>Address</strong>
                    <span>{{ $address ?: 'N/A' }}</span>

                    <strong>City</strong>
                    <span>{{ $city ?: 'N/A' }}</span>

                    <strong>State</strong>
                    <span>{{ $state ?: 'N/A' }}</span>

                    <strong>ZIP</strong>
                    <span>{{ $zip ?: 'N/A' }}</span>

                    <strong>Phone</strong>
                    <span>{{ $phone ?: 'N/A' }}</span>
                </div>

                @if($mapQuery !== ',')
                    <a target="_blank" href="https://www.google.com/maps?q={{ urlencode($mapQuery) }}" class="sg-map-link">
                        <i class="fa-solid fa-location-dot"></i>
                        View on Map
                    </a>
                @endif
            </div>
        @empty
            <div class="sg-map-card">No records found.</div>
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