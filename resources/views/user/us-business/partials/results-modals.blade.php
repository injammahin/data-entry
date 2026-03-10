<div class="sg-modal-overlay" id="saveListModal">
    <div class="sg-modal-card">
        <div class="sg-modal-header">
            <span>Save List</span>
            <button type="button" class="sg-modal-close" data-modal-close>&times;</button>
        </div>

        <form method="POST" action="{{ route('user.us-business.save-list') }}">
            @csrf

            @foreach(request()->except('page', 'name') as $key => $value)
                @if(is_array($value))
                    @foreach($value as $single)
                        <input type="hidden" name="{{ $key }}[]" value="{{ $single }}">
                    @endforeach
                @else
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach

            <div class="sg-modal-body">
                <input type="text" name="name" class="sg-input" placeholder="Enter a list name" required>
            </div>

            <div class="sg-modal-footer">
                <button type="button" class="sg-btn light" data-modal-close>Cancel</button>
                <button type="submit" class="sg-btn primary">Save</button>
            </div>
        </form>
    </div>
</div>

<div class="sg-modal-overlay" id="columnsModal">
    <div class="sg-modal-card">
        <div class="sg-modal-header">
            <span>Select Columns</span>
            <button type="button" class="sg-modal-close" data-modal-close>&times;</button>
        </div>

        <form method="GET" action="{{ url()->current() }}">
            @foreach(request()->except('page', 'columns') as $key => $value)
                @if(is_array($value))
                    @foreach($value as $single)
                        <input type="hidden" name="{{ $key }}[]" value="{{ $single }}">
                    @endforeach
                @else
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach

            <div class="sg-modal-body">
                <div class="sg-column-grid">
                    @foreach($availableColumns as $column)
                        <label class="sg-column-item">
                            <input
                                type="checkbox"
                                name="columns[]"
                                value="{{ $column }}"
                                {{ in_array($column, $visibleColumns, true) ? 'checked' : '' }}
                            >
                            <span>{{ $columnAliases[strtolower(trim(str_replace(['-', ' '], '_', $column)))] ?? $column }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="sg-modal-footer">
                <a href="{{ url()->current() . '?' . http_build_query(request()->except('columns', 'page')) }}" class="sg-btn light">
                    Reset
                </a>
                <button type="submit" class="sg-btn dark">Apply Columns</button>
            </div>
        </form>
    </div>
</div>

</div>