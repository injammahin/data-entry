@extends('layouts.user')

@section('title', 'Business Search Results')

@section('content')
    @php
        $totalRecords = $records->total();
        $currentPage = $records->currentPage();
        $lastPage = $records->lastPage();

        $columnAliases = [
            'business_name' => 'BUSINESS NAME',
            'executive_info' => 'EXECUTIVE INFO',
            'phone' => 'PHONE',
            'phone_number' => 'PHONE',
            'address' => 'ADDRESS',
            'city' => 'CITY',
            'zip_code' => 'ZIP CODE',
            'zip' => 'ZIP CODE',
            'sic_code' => 'SIC CODE',
            'sic_description' => 'SIC DESCRIPTION',
        ];

        $orderedPriority = [
            'business_name',
            'executive_info',
            'phone',
            'phone_number',
            'address',
            'city',
            'zip_code',
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

        $activeFilters = [
            'Business Name' => request('business_name'),
            'Executive First Name' => request('executive_first_name'),
            'Executive Last Name' => request('executive_last_name'),
            'State' => $selectedState?->name,
            'City' => request('city'),
            'Address' => request('address'),
            'ZIP Code' => request('zip_code'),
            'Phone Number' => request('phone_number'),
        ];
    @endphp

    <style>
        .records-grid-wrapper {
            background: #f5f6f8;
            border: 1px solid #cfd4da;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(31, 53, 89, .08);
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
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 6px;
        }

        .toolbar-btn:hover {
            background: #e9edf2;
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

        .page-title-bar {
            display:flex;
            justify-content:space-between;
            align-items:flex-start;
            gap:16px;
            margin-bottom:18px;
            flex-wrap:wrap;
        }

        .page-title-bar h2 {
            margin-bottom:6px;
        }

        .search-chip-wrap{
            display:flex;
            gap:10px;
            flex-wrap:wrap;
            margin-top:14px;
        }

        .search-chip{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding:8px 12px;
            border-radius:999px;
            background:#eef6ff;
            border:1px solid #d8e8fb;
            color:#275181;
            font-size:12px;
            font-weight:700;
        }

        .action-btn{
            text-decoration:none;
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding:12px 16px;
            border-radius:12px;
            font-weight:700;
            transition:.25s ease;
        }

        .action-btn.primary{
            background:linear-gradient(135deg, #54b7ff, #2d74ff);
            color:#fff;
            box-shadow:0 14px 25px rgba(44, 114, 255, .22);
        }

        .action-btn.light{
            background:#f3f6fa;
            color:#30445f;
            border:1px solid #d9e1ea;
        }

        .action-btn:hover{
            transform:translateY(-2px);
        }
    </style>

    <div class="page-title-bar">
        <div>
            <h2 class="mb-1">Business Search Results</h2>
            <div class="mini-muted">
                Results based on your selected search filters
            </div>

            <div class="search-chip-wrap">
                @foreach($activeFilters as $label => $value)
                    @if(!empty($value))
                        <div class="search-chip">
                            <i class="fa-solid fa-filter"></i>
                            {{ $label }}: {{ $value }}
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('user.us-business.index') }}" class="action-btn light">
                <i class="fa-solid fa-arrow-left"></i>
                Edit Search
            </a>

            <a href="{{ route('user.us-business.results', array_merge(request()->query(), ['page' => 1])) }}" class="action-btn primary">
                <i class="fa-solid fa-rotate-right"></i>
                Refresh Results
            </a>
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
                    <a href="javascript:void(0)" class="toolbar-btn">
                        <i class="fa-solid fa-bookmark"></i> Save
                    </a>
                    <a href="javascript:void(0)" class="toolbar-btn">
                        <i class="fa-solid fa-file-export"></i> Export
                    </a>
                    <a href="javascript:void(0)" class="toolbar-btn">
                        <i class="fa-solid fa-envelope"></i> Direct Mail
                    </a>
                </div>
            </div>

            <div>
                <div class="toolbar-group-title">Campaigns</div>
                <div class="d-flex flex-wrap gap-1">
                    <a href="javascript:void(0)" class="toolbar-btn">
                        <i class="fa-solid fa-paper-plane"></i> Email
                    </a>
                    <a href="javascript:void(0)" class="toolbar-btn">
                        <i class="fa-solid fa-rectangle-ad"></i> Display Ads
                    </a>
                    <a href="javascript:void(0)" class="toolbar-btn">
                        <i class="fa-solid fa-bullseye"></i> Intent Data
                    </a>
                    <a href="javascript:void(0)" class="toolbar-btn">
                        <i class="fa-solid fa-ellipsis"></i> More
                    </a>
                </div>
            </div>
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

                <form method="GET" action="{{ route('user.us-business.results') }}" class="d-inline">
                    <input type="hidden" name="business_name" value="{{ request('business_name') }}">
                    <input type="hidden" name="executive_first_name" value="{{ request('executive_first_name') }}">
                    <input type="hidden" name="executive_last_name" value="{{ request('executive_last_name') }}">
                    <input type="hidden" name="state_id" value="{{ request('state_id') }}">
                    <input type="hidden" name="city" value="{{ request('city') }}">
                    <input type="hidden" name="address" value="{{ request('address') }}">
                    <input type="hidden" name="zip_code" value="{{ request('zip_code') }}">
                    <input type="hidden" name="phone_number" value="{{ request('phone_number') }}">
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
                    <button type="button" class="pager-btn" {{ $records->onFirstPage() ? 'disabled' : '' }}>&lsaquo;</button>
                </a>

                <a href="{{ $records->nextPageUrl() ?: '#' }}" class="text-decoration-none" @if($records->hasMorePages()) @else onclick="return false;" @endif>
                    <button type="button" class="pager-btn" {{ $records->hasMorePages() ? '' : 'disabled' }}>&rsaquo;</button>
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