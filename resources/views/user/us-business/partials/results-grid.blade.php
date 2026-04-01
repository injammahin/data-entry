@php
    $currentPage = method_exists($records, 'currentPage') ? $records->currentPage() : 1;
    $perPageValue = $perPage ?? (method_exists($records, 'perPage') ? $records->perPage() : count($records));
    $rowStart = (($currentPage - 1) * $perPageValue) + 1;

    $headerLabel = function ($header) use ($columnAliases) {
        $normalized = strtolower(trim(str_replace(['-', ' '], '_', $header)));
        return $columnAliases[$normalized] ?? strtoupper(str_replace('_', ' ', $header));
    };

    $cellValue = function ($record, $column) {
        $data = is_array($record->data_json ?? null) ? $record->data_json : [];

        $normalized = strtolower(trim(str_replace(['-', ' '], '_', $column)));

        if ($normalized === 'email') {
            return $data['Email'] ?? $data['email'] ?? '';
        }

        if ($normalized === 'email_hash') {
            return $data['Email_Hash'] ?? $data['email_hash'] ?? '';
        }

        if ($normalized === 'email_status') {
            return $data['Email_Status'] ?? '';
        }

        return $data[$column] ?? '';
    };
@endphp

<div class="sg-grid-wrap">
    <table class="sg-grid-table">
        <thead>
            <tr>
                <th class="sg-col-check">
                    <input type="checkbox" id="sg-check-all">
                </th>
                <th class="sg-col-row">#</th>
                <th class="sg-col-status">
                    <i class="fa-solid fa-fire"></i>
                </th>

                @foreach ($visibleColumns as $header)
                    <th>{{ $headerLabel($header) }}</th>
                @endforeach
            </tr>
        </thead>

        <tbody>
            @forelse ($records as $index => $record)
                @php
                    $rowNumber = $rowStart + $index;
                    $emailStatus = data_get($record->data_json, 'Email_Status', '');
                    $rowStatusClass = $emailStatus === 'REAL EMAIL'
                        ? 'real-email'
                        : ($emailStatus === 'HASHED EMAIL ONLY' ? 'hashed-email' : 'no-email');
                @endphp

                <tr>
                    <td class="sg-col-check">
                        <input type="checkbox" class="sg-row-check" value="{{ $record->id }}">
                    </td>

                    <td class="sg-col-row">{{ $rowNumber }}</td>

                    <td class="sg-col-status">
                        <span class="sg-status-dot {{ $rowStatusClass }}"></span>
                    </td>

                    @foreach ($visibleColumns as $column)
                        @php
                            $value = $cellValue($record, $column);

                            if (is_array($value)) {
                                $value = json_encode($value);
                            }

                            $value = trim((string) $value);
                        @endphp

                        <td title="{{ $value }}">
                            {{ $value !== '' ? $value : '—' }}
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($visibleColumns) + 3 }}">
                        <div class="sg-empty-state">
                            No records found for the selected filters.
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(method_exists($records, 'links'))
    <div class="sg-grid-pagination">
        {{ $records->links() }}
    </div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkAll = document.getElementById('sg-check-all');
        const rowChecks = document.querySelectorAll('.sg-row-check');

        if (checkAll) {
            checkAll.addEventListener('change', function () {
                rowChecks.forEach(item => {
                    item.checked = checkAll.checked;
                });
            });
        }
    });
</script>