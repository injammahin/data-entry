@extends('layouts.admin')

@section('title', 'Records')

@section('content')
    @php
        $totalRecords = $records->total();
        $currentPage = $records->currentPage();
        $lastPage = $records->lastPage();

        $columnAliases = [
            'business_name' => 'BUSINESS NAME',
            'executive_info' => 'EXECUTIVE INFO',
            'phone' => 'PHONE',
            'address' => 'ADDRESS',
            'sic_code' => 'SIC CODE',
            'sic_description' => 'SIC DESCRIPTION',
        ];

        $orderedPriority = [
            'business_name',
            'executive_info',
            'phone',
            'address',
            'sic_code',
            'sic_description',
        ];

        $normalizedHeaders = collect($headers)->map(function ($header) {
            return [
                'original' => $header,
                'normalized' => strtolower(trim(str_replace(['-', ' '], '_', $header))),
            ];
        });

        $orderedHeaders = [];

        foreach ($orderedPriority as $priorityKey) {
            foreach ($normalizedHeaders as $headerItem) {
                if ($headerItem['normalized'] === $priorityKey) {
                    $orderedHeaders[] = $headerItem['original'];
                }
            }
        }

        foreach ($headers as $header) {
            if (!in_array($header, $orderedHeaders, true)) {
                $orderedHeaders[] = $header;
            }
        }

        $displayHeaders = $orderedHeaders;

        function headerLabel($header, $aliases) {
            $normalized = strtolower(trim(str_replace(['-', ' '], '_', $header)));
            return $aliases[$normalized] ?? strtoupper($header);
        }
    @endphp

    <style>
        .records-grid-wrapper {
            background: #f5f6f8;
            border: 1px solid #cfd4da;
        }

        .records-topbar {
            background: #f3f4f6;
            border-bottom: 1px solid #cfd4da;
            padding: 14px 18px;
        }

        .records-count-box {
            min-width: 130px;
            border-right: 1px solid #cfd4da;
            padding-right: 18px;
            margin-right: 18px;
        }

        .records-count-box .count {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
            color: #3b4b5c;
        }

        .records-count-box .label {
            font-size: 12px;
            color: #7a8794;
            text-transform: uppercase;
        }

        .toolbar-group-title {
            font-size: 10px;
            text-transform: uppercase;
            color: #7a8794;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .toolbar-btn {
            border: none;
            background: transparent;
            font-size: 13px;
            color: #596776;
            padding: 6px 10px;
        }

        .toolbar-btn:hover {
            background: #e9edf2;
            border-radius: 4px;
        }

        .records-filter-bar {
            background: #ffffff;
            border-bottom: 1px solid #cfd4da;
            padding: 14px 18px;
        }

        .grid-table-wrap {
            overflow: auto;
            max-height: 700px;
            background: #fff;
        }

        .records-grid-table {
            width: max-content;
            min-width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 13px;
        }

        .records-grid-table thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            background: #f1f3f5;
            color: #37424d;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11px;
            border-bottom: 1px solid #cfd4da;
            border-right: 1px solid #d9dee3;
            padding: 12px 10px;
            white-space: nowrap;
        }

        .records-grid-table tbody td {
            border-bottom: 1px solid #eceff3;
            border-right: 1px solid #eef1f4;
            padding: 10px;
            vertical-align: top;
            background: #fff;
            color: #22303c;
            white-space: normal;
        }

        .records-grid-table tbody tr:nth-child(even) td {
            background: #fbfcfd;
        }

        .records-grid-table tbody tr:hover td {
            background: #eef6ff;
        }

        .col-checkbox {
            width: 42px;
            min-width: 42px;
            text-align: center;
        }

        .col-rownum {
            width: 58px;
            min-width: 58px;
            text-align: center;
            color: #7b8794;
        }

        .cell-multiline {
            white-space: pre-line;
            line-height: 1.35;
            min-width: 170px;
            max-width: 260px;
        }

        .cell-business-name {
            font-weight: 600;
            color: #1e2a35;
        }

        .grid-footer {
            background: #f3f4f6;
            border-top: 1px solid #cfd4da;
            padding: 10px 14px;
        }

        .pager-btn {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: none;
            background: #6ea8ff;
            color: #fff;
            font-weight: bold;
        }

        .pager-btn:disabled {
            background: #cad3df;
        }

        .page-box {
            width: 56px;
            text-align: center;
            border: 1px solid #cfd4da;
            border-radius: 3px;
            background: #fff;
            height: 30px;
        }

        .mini-muted {
            font-size: 12px;
            color: #6c7a89;
        }

        .records-title-row {
            margin-bottom: 14px;
        }
    </style>

    <div class="records-title-row d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-1">Records</h2>
            <div class="mini-muted">
                Browse all imported data in grid view
            </div>
        </div>
    </div>

    <div class="records-grid-wrapper">
        <div class="records-topbar d-flex align-items-start flex-wrap gap-4">
            <div class="records-count-box">
                <div class="count">{{ number_format($totalRecords) }}</div>
                <div class="label">Records</div>
            </div>

            <div>
                <div class="toolbar-group-title">List Options</div>
                <div class="d-flex flex-wrap gap-1">
                    <button type="button" class="toolbar-btn">Save</button>
                    <button type="button" class="toolbar-btn">Export</button>
                    <button type="button" class="toolbar-btn">Direct Mail</button>
                </div>
            </div>

            <div>
                <div class="toolbar-group-title">Campaigns</div>
                <div class="d-flex flex-wrap gap-1">
                    <button type="button" class="toolbar-btn">Email</button>
                    <button type="button" class="toolbar-btn">Display Ads</button>
                    <button type="button" class="toolbar-btn">Intent Data</button>
                    <button type="button" class="toolbar-btn">More</button>
                </div>
            </div>
        </div>

        <div class="records-filter-bar">
            <form method="GET" action="{{ route('admin.records.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">State</label>
                        <select name="state_id" class="form-select">
                            <option value="">All States</option>
                            @foreach($states as $state)
                                <option value="{{ $state->id }}" {{ request('state_id') == $state->id ? 'selected' : '' }}>
                                    {{ $state->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Import Batch</label>
                        <select name="import_id" class="form-select">
                            <option value="">All Imports</option>
                            @foreach($imports as $import)
                                <option value="{{ $import->id }}" {{ request('import_id') == $import->id ? 'selected' : '' }}>
                                    #{{ $import->id }} - {{ $import->original_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Keyword</label>
                        <input
                            type="text"
                            name="keyword"
                            class="form-control"
                            value="{{ request('keyword') }}"
                            placeholder="Search records"
                        >
                    </div>

                    <div class="col-md-1">
                        <label class="form-label">Per Page</label>
                        <select name="per_page" class="form-select">
                            <option value="30" {{ $perPage == 30 ? 'selected' : '' }}>30</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary">Filter</button>
                            <a href="{{ route('admin.records.index') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="grid-table-wrap">
            <table class="records-grid-table">
                <thead>
                    <tr>
                        <th class="col-checkbox">
                            <input type="checkbox">
                        </th>
                        <th class="col-rownum">#</th>

                        @if(count($displayHeaders))
                            @foreach($displayHeaders as $header)
                                <th>{{ headerLabel($header, $columnAliases) }}</th>
                            @endforeach
                        @else
                            <th>STATE</th>
                            <th>IMPORT ID</th>
                            <th>ROW NUMBER</th>
                            <th>DATA</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @forelse($records as $index => $record)
                        <tr>
                            <td class="col-checkbox text-center">
                                <input type="checkbox" value="{{ $record->id }}">
                            </td>

                            <td class="col-rownum">
                                {{ ($records->firstItem() ?? 0) + $index }}
                            </td>

                            @if(count($displayHeaders))
                                @foreach($displayHeaders as $header)
                                    @php
                                        $value = is_array($record->data_json) ? ($record->data_json[$header] ?? '') : '';
                                        $headerKey = strtolower(trim(str_replace(['-', ' '], '_', $header)));
                                    @endphp

                                    <td class="cell-multiline">
                                        @if($headerKey === 'business_name')
                                            <span class="cell-business-name">{{ $value }}</span>
                                        @elseif(in_array($headerKey, ['executive_info', 'address']))
                                            {!! nl2br(e($value)) !!}
                                        @else
                                            {{ $value }}
                                        @endif
                                    </td>
                                @endforeach
                            @else
                                <td>{{ $record->state->name ?? '-' }}</td>
                                <td>#{{ $record->import_id }}</td>
                                <td>{{ $record->row_number }}</td>
                                <td>
                                    @if(is_array($record->data_json))
                                        @foreach($record->data_json as $key => $value)
                                            <div class="mb-1">
                                                <strong>{{ $key }}:</strong> {{ $value }}
                                            </div>
                                        @endforeach
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($displayHeaders) ? count($displayHeaders) + 2 : 6 }}" class="text-center py-4">
                                No records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="grid-footer d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-2 mini-muted">
                <span>Page</span>

                <form method="GET" action="{{ route('admin.records.index') }}" class="d-inline">
                    <input type="hidden" name="state_id" value="{{ request('state_id') }}">
                    <input type="hidden" name="import_id" value="{{ request('import_id') }}">
                    <input type="hidden" name="keyword" value="{{ request('keyword') }}">
                    <input type="hidden" name="per_page" value="{{ $perPage }}">

                    <input
                        type="number"
                        name="page"
                        value="{{ $currentPage }}"
                        min="1"
                        max="{{ $lastPage }}"
                        class="page-box"
                        onchange="this.form.submit()"
                    >
                </form>

                <span>of {{ number_format($lastPage) }}</span>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ $records->previousPageUrl() ?: '#' }}" class="text-decoration-none" @if(!$records->onFirstPage()) @else onclick="return false;" @endif>
                    <button class="pager-btn" {{ $records->onFirstPage() ? 'disabled' : '' }}>&lsaquo;</button>
                </a>

                <a href="{{ $records->nextPageUrl() ?: '#' }}" class="text-decoration-none" @if($records->hasMorePages()) @else onclick="return false;" @endif>
                    <button class="pager-btn" {{ $records->hasMorePages() ? '' : 'disabled' }}>&rsaquo;</button>
                </a>
            </div>

            <div class="mini-muted">
                Showing {{ number_format($records->firstItem() ?? 0) }}
                to {{ number_format($records->lastItem() ?? 0) }}
                of {{ number_format($records->total()) }}
            </div>
        </div>
    </div>
@endsection