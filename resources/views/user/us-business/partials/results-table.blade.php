@php
    $currentPage = $records->currentPage();
    $lastPage = $records->lastPage();

    function sgHeaderLabel($header, $aliases) {
        $normalized = strtolower(trim(str_replace(['-', ' '], '_', $header)));
        return $aliases[$normalized] ?? strtoupper($header);
    }
@endphp

<div class="sg-grid-wrap">
    <table class="sg-grid-table">
        <thead>
            <tr>
                <th class="sg-col-check"><input type="checkbox"></th>
                <th class="sg-col-row">#</th>
                <th class="sg-col-status"><i class="fa-solid fa-fire"></i></th>

                @foreach($visibleColumns as $header)
                    <th>{{ sgHeaderLabel($header, $columnAliases) }}</th>
                @endforeach
            </tr>
        </thead>

        <tbody>
            @forelse($records as $index => $record)
                <tr>
                    <td class="sg-col-check">
                        <input type="checkbox" value="{{ $record->id }}">
                    </td>

                    <td class="sg-col-row">
                        {{ ($records->firstItem() ?? 0) + $index }}
                    </td>

                    <td class="sg-col-status">
                        <i class="fa-solid fa-fire"></i>
                    </td>

                    @foreach($visibleColumns as $header)
                        @php
                            $value = is_array($record->data_json) ? ($record->data_json[$header] ?? '') : '';
                            $headerKey = strtolower(trim(str_replace(['-', ' '], '_', $header)));
                        @endphp

                        <td class="sg-multiline">
                            @if($headerKey === 'business_name')
                                <span class="sg-business-name">{{ $value }}</span>
                            @elseif(in_array($headerKey, ['executive_info', 'address']))
                                {!! nl2br(e($value)) !!}
                            @else
                                {{ $value }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($visibleColumns) + 3 }}" style="text-align:center; padding:30px;">
                        No records found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="sg-footer">
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
                max="{{ $lastPage }}"
                value="{{ $currentPage }}"
                onchange="this.form.submit()"
            >
        </form>

        <span>of {{ number_format($lastPage) }}</span>
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