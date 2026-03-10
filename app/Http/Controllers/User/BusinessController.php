<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Record;
use App\Models\SearchList;
use App\Models\State;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelWriter;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BusinessController extends Controller
{
    private const STATES_CACHE_TTL = 86400;      // 24h
    private const SEARCH_CACHE_TTL = 600;        // 10 min
    private const PAGE_CACHE_TTL = 300;          // 5 min
    private const SNAPSHOT_MAX_RECORDS = 10000;  // above this, do not snapshot pivot rows

    private array $filterFields = [
        'business_name',
        'executive_first_name',
        'executive_last_name',
        'state_id',
        'city',
        'address',
        'zip_code',
        'phone_number',
        'columns',
        'per_page',
    ];

    public function index()
    {
        $states = Cache::remember(
            'user_business_states_all',
            self::STATES_CACHE_TTL,
            fn () => State::select('id', 'name')->orderBy('name')->get()
        );

        return view('user.us-business.index', compact('states'));
    }

    public function results(Request $request)
    {
        $this->validateSearchRequest($request);

        if ($redirect = $this->ensureHasFilters($request)) {
            return $redirect;
        }

        $context = $this->buildPageContext($request, 30);

        return view('user.us-business.results-list', $context + [
            'activeTab' => 'list',
        ]);
    }

    public function insights(Request $request)
    {
        $this->validateSearchRequest($request);

        if ($redirect = $this->ensureHasFilters($request)) {
            return $redirect;
        }

        $criteriaKey = $this->criteriaCacheKey($request);

        $insights = Cache::remember(
            "user_business_insights:{$criteriaKey}",
            self::SEARCH_CACHE_TTL,
            function () use ($request) {
                $baseQuery = $this->buildFilteredQuery($request);

                return [
                    'total_results' => (clone $baseQuery)->reorder()->count('records.id'),
                    'state_count' => (clone $baseQuery)->reorder()->distinct('records.state_id')->count('records.state_id'),
                    'email_count' => $this->countFilledJsonField(
                        clone $baseQuery,
                        ['email', 'Email', 'EMAIL', 'email_address', 'Email Address'],
                        true
                    ),
                    'direct_mail_count' => $this->countFilledJsonField(
                        clone $baseQuery,
                        ['address', 'Address', 'ADDRESS', 'street_address', 'Street Address', 'mailing_address', 'Mailing Address']
                    ),
                    'top_cities' => $this->topJsonValues(
                        clone $baseQuery,
                        ['city', 'City', 'CITY'],
                        8
                    ),
                    'top_sic_descriptions' => $this->topJsonValues(
                        clone $baseQuery,
                        ['sic_description', 'SIC Description', 'SIC DESCRIPTION'],
                        8
                    ),
                    'top_titles' => $this->topJsonValues(
                        clone $baseQuery,
                        ['executive_title', 'Executive Title', 'EXECUTIVE TITLE'],
                        8
                    ),
                ];
            }
        );

        $context = $this->buildPageContext($request, 30, [
            'emailCount' => $insights['email_count'] ?? 0,
            'directMailCount' => $insights['direct_mail_count'] ?? 0,
        ]);

        return view('user.us-business.results-insights', $context + [
            'activeTab' => 'insights',
            'insights' => $insights,
        ]);
    }

    public function details(Request $request)
    {
        $this->validateSearchRequest($request);

        if ($redirect = $this->ensureHasFilters($request)) {
            return $redirect;
        }

        $context = $this->buildPageContext($request, 12);

        return view('user.us-business.results-details', $context + [
            'activeTab' => 'details',
        ]);
    }

    public function map(Request $request)
    {
        $this->validateSearchRequest($request);

        if ($redirect = $this->ensureHasFilters($request)) {
            return $redirect;
        }

        $context = $this->buildPageContext($request, 12);

        return view('user.us-business.results-map', $context + [
            'activeTab' => 'map',
        ]);
    }

    public function saveList(Request $request)
    {
        $this->validateSearchRequest($request);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        if ($redirect = $this->ensureHasFilters($request)) {
            return $redirect;
        }

        $criteriaKey = $this->criteriaCacheKey($request);
        $baseQuery = $this->buildFilteredQuery($request);

        $totalRecords = Cache::remember(
            "user_business_total:{$criteriaKey}",
            self::SEARCH_CACHE_TTL,
            fn () => (clone $baseQuery)->count('records.id')
        );

        if ($totalRecords === 0) {
            return back()->with('error', 'No records found to save.');
        }

        $headers = $this->getOrderedHeaders($request, $criteriaKey, clone $baseQuery);
        $visibleColumns = $this->resolveVisibleColumns($request, $headers);

        $searchList = SearchList::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'criteria_json' => $request->only([
                'business_name',
                'executive_first_name',
                'executive_last_name',
                'state_id',
                'city',
                'address',
                'zip_code',
                'phone_number',
            ]),
            'visible_columns' => $visibleColumns,
            'total_records' => $totalRecords,
        ]);

        /*
         * FAST MODE:
         * Large result sets should not create millions of pivot rows synchronously.
         * Snapshot only smaller result sets; for large sets store smart criteria only.
         */
        if ($totalRecords <= self::SNAPSHOT_MAX_RECORDS) {
            (clone $baseQuery)
                ->select('records.id')
                ->chunkById(1000, function ($rows) use ($searchList) {
                    $payload = [];

                    foreach ($rows as $row) {
                        $payload[] = [
                            'search_list_id' => $searchList->id,
                            'record_id' => $row->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }

                    if (! empty($payload)) {
                        DB::table('search_list_record')->insertOrIgnore($payload);
                    }
                }, 'records.id');
        }

        return back()->with(
            'success',
            $totalRecords <= self::SNAPSHOT_MAX_RECORDS
                ? 'Search list saved successfully.'
                : 'Search list saved successfully. Large result set stored in fast smart mode.'
        );
    }

    public function exportCsv(Request $request)
    {
        $this->validateSearchRequest($request);

        if ($redirect = $this->ensureHasFilters($request)) {
            return $redirect;
        }

        $baseQuery = $this->buildFilteredQuery($request);
        $criteriaKey = $this->criteriaCacheKey($request);
        $headers = $this->getOrderedHeaders($request, $criteriaKey, clone $baseQuery);
        $visibleColumns = $this->resolveVisibleColumns($request, $headers);
        $headings = array_map(fn ($column) => $this->headerLabel($column), $visibleColumns);

        $export = new class(clone $baseQuery, $visibleColumns, $headings) implements FromQuery, WithHeadings, WithMapping {
            public function __construct(
                private Builder $query,
                private array $visibleColumns,
                private array $headings
            ) {
            }

            public function query()
            {
                return $this->query;
            }

            public function headings(): array
            {
                return $this->headings;
            }

            public function map($record): array
            {
                $data = is_array($record->data_json)
                    ? $record->data_json
                    : (json_decode($record->getRawOriginal('data_json') ?: '[]', true) ?: []);

                $row = [];

                foreach ($this->visibleColumns as $column) {
                    $value = $data[$column] ?? '';
                    $row[] = is_array($value)
                        ? json_encode($value)
                        : preg_replace("/\r\n|\r|\n/", ' ', (string) $value);
                }

                return $row;
            }
        };

        return Excel::download($export, 'business-search-results.csv', ExcelWriter::CSV);
    }

    public function exportXlsx(Request $request)
    {
        $this->validateSearchRequest($request);

        if ($redirect = $this->ensureHasFilters($request)) {
            return $redirect;
        }

        $baseQuery = $this->buildFilteredQuery($request);
        $criteriaKey = $this->criteriaCacheKey($request);
        $headers = $this->getOrderedHeaders($request, $criteriaKey, clone $baseQuery);
        $visibleColumns = $this->resolveVisibleColumns($request, $headers);
        $headings = array_map(fn ($column) => $this->headerLabel($column), $visibleColumns);

        $export = new class(clone $baseQuery, $visibleColumns, $headings) implements FromQuery, WithHeadings, WithMapping {
            public function __construct(
                private Builder $query,
                private array $visibleColumns,
                private array $headings
            ) {
            }

            public function query()
            {
                return $this->query;
            }

            public function headings(): array
            {
                return $this->headings;
            }

            public function map($record): array
            {
                $data = is_array($record->data_json)
                    ? $record->data_json
                    : (json_decode($record->getRawOriginal('data_json') ?: '[]', true) ?: []);

                $row = [];

                foreach ($this->visibleColumns as $column) {
                    $value = $data[$column] ?? '';
                    $row[] = is_array($value)
                        ? json_encode($value)
                        : preg_replace("/\r\n|\r|\n/", ' ', (string) $value);
                }

                return $row;
            }
        };

        return Excel::download($export, 'business-search-results.xlsx', ExcelWriter::XLSX);
    }

    private function validateSearchRequest(Request $request): void
    {
        $request->validate([
            'business_name' => ['nullable', 'string', 'max:255'],
            'executive_first_name' => ['nullable', 'string', 'max:255'],
            'executive_last_name' => ['nullable', 'string', 'max:255'],
            'state_id' => ['nullable', 'integer', 'exists:states,id'],
            'city' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'zip_code' => ['nullable', 'string', 'max:50'],
            'phone_number' => ['nullable', 'string', 'max:50'],
            'columns' => ['nullable', 'array'],
            'per_page' => ['nullable', 'integer'],
            'page' => ['nullable', 'integer'],
        ]);
    }

    private function ensureHasFilters(Request $request)
    {
        $hasAnyFilter = collect([
            'business_name',
            'executive_first_name',
            'executive_last_name',
            'state_id',
            'city',
            'address',
            'zip_code',
            'phone_number',
        ])->contains(fn ($field) => $request->filled($field));

        if (! $hasAnyFilter) {
            return redirect()
                ->route('user.us-business.index')
                ->with('error', 'Please fill at least one search field.');
        }

        return null;
    }

    private function buildPageContext(Request $request, int $defaultPerPage, ?array $metricsOverride = null): array
    {
        $criteriaKey = $this->criteriaCacheKey($request);
        $baseQuery = $this->buildFilteredQuery($request);

        $perPage = (int) $request->get('per_page', $defaultPerPage);
        $allowed = [12, 30, 50, 100];

        if (! in_array($perPage, $allowed, true)) {
            $perPage = $defaultPerPage;
        }

        $page = max(1, (int) $request->get('page', 1));

        $total = Cache::remember(
            "user_business_total:{$criteriaKey}",
            self::SEARCH_CACHE_TTL,
            fn () => (clone $baseQuery)->reorder()->count('records.id')
        );

        $pageIds = [];

        if ($total > 0) {
            $pageIds = Cache::remember(
                "user_business_page_ids:{$criteriaKey}:{$page}:{$perPage}",
                self::PAGE_CACHE_TTL,
                fn () => (clone $baseQuery)
                    ->forPage($page, $perPage)
                    ->pluck('records.id')
                    ->all()
            );
        }

        $pageRecords = $this->fetchRecordsByIds($pageIds);

        $records = new LengthAwarePaginator(
            $pageRecords,
            $total,
            $perPage,
            $page,
            [
                'path' => url()->current(),
                'query' => $request->query(),
            ]
        );

        $headers = $this->getOrderedHeaders($request, $criteriaKey, clone $baseQuery, $pageRecords);
        $visibleColumns = $this->resolveVisibleColumns($request, $headers);

        $selectedState = null;
        if ($request->filled('state_id')) {
            $selectedState = Cache::remember(
                'user_business_state_' . $request->state_id,
                self::STATES_CACHE_TTL,
                fn () => State::select('id', 'name')->find($request->state_id)
            );
        }

        /*
        * FAST MODE:
        * Non-insight pages should not run heavy metric counts every time.
        * Use override if provided, otherwise read from cache only.
        */
        if ($metricsOverride !== null) {
            $metrics = [
                'emailCount' => (int) ($metricsOverride['emailCount'] ?? 0),
                'directMailCount' => (int) ($metricsOverride['directMailCount'] ?? 0),
            ];

            Cache::put("user_business_metrics:{$criteriaKey}", $metrics, self::SEARCH_CACHE_TTL);
        } else {
            $metrics = Cache::get("user_business_metrics:{$criteriaKey}", [
                'emailCount' => 0,
                'directMailCount' => 0,
            ]);
        }

        return [
            'records' => $records,
            'headers' => $headers,
            'visibleColumns' => $visibleColumns,
            'availableColumns' => $headers,
            'columnAliases' => $this->columnAliases(),
            'activeFilters' => $this->activeFilters($request, $selectedState),
            'selectedState' => $selectedState,
            'emailCount' => $metrics['emailCount'] ?? 0,
            'directMailCount' => $metrics['directMailCount'] ?? 0,
            'perPage' => $perPage,
        ];
    }

    private function buildFilteredQuery(Request $request): Builder
    {
        $query = Record::query()
            ->select([
                'records.id',
                'records.state_id',
                'records.import_id',
                'records.row_number',
                'records.data_json',
            ])
            ->orderBy('records.id');

        if ($request->filled('state_id')) {
            $query->where('records.state_id', $request->state_id);
        }

        $this->applySearchOnJsonField($query, $request->business_name, [
            'business_name',
            'Business Name',
            'BUSINESS NAME',
            'company_name',
            'Company Name',
        ]);

        $this->applySearchOnJsonField($query, $request->executive_first_name, [
            'executive_first_name',
            'Executive First Name',
            'first_name',
            'First Name',
            'owner_first_name',
            'Owner First Name',
            'executive_info',
            'Executive Info',
            'EXECUTIVE INFO',
        ]);

        $this->applySearchOnJsonField($query, $request->executive_last_name, [
            'executive_last_name',
            'Executive Last Name',
            'last_name',
            'Last Name',
            'owner_last_name',
            'Owner Last Name',
            'executive_info',
            'Executive Info',
            'EXECUTIVE INFO',
        ]);

        $this->applySearchOnJsonField($query, $request->city, [
            'city',
            'City',
            'CITY',
        ]);

        $this->applySearchOnJsonField($query, $request->address, [
            'address',
            'Address',
            'ADDRESS',
            'street_address',
            'Street Address',
            'mailing_address',
            'Mailing Address',
        ]);

        $this->applySearchOnJsonField($query, $request->zip_code, [
            'zip',
            'Zip',
            'ZIP',
            'zip_code',
            'Zip Code',
            'ZIP CODE',
            'postal_code',
            'Postal Code',
        ]);

        $this->applySearchOnJsonField($query, $request->phone_number, [
            'phone',
            'Phone',
            'PHONE',
            'phone_number',
            'Phone Number',
            'PHONE NUMBER',
            'telephone',
            'Telephone',
        ]);

        return $query;
    }

    private function applySearchOnJsonField(Builder $query, ?string $value, array $possibleKeys): void
    {
        $value = trim((string) $value);

        if ($value === '') {
            return;
        }

        $driver = DB::connection()->getDriverName();
        $lowerValue = mb_strtolower($value);

        $query->where(function (Builder $innerQuery) use ($driver, $possibleKeys, $lowerValue, $value) {
            if ($driver === 'mysql') {
                /*
                 * FAST MODE:
                 * Do NOT fallback to full raw data_json LIKE on MySQL.
                 * That makes 100M-row scans much worse.
                 */
                $expr = $this->jsonCoalesceExpression($possibleKeys);
                $innerQuery->whereRaw(
                    "LOWER(COALESCE({$expr}, '')) LIKE ?",
                    ['%' . $lowerValue . '%']
                );

                return;
            }

            $innerQuery->where('records.data_json', 'like', '%' . $value . '%');
        });
    }

    private function fetchRecordsByIds(array $ids): Collection
    {
        if (empty($ids)) {
            return collect();
        }

        $items = Record::query()
            ->select([
                'records.id',
                'records.state_id',
                'records.import_id',
                'records.row_number',
                'records.data_json',
            ])
            ->with(['state:id,name'])
            ->whereIn('records.id', $ids)
            ->get()
            ->keyBy('id');

        return collect($ids)
            ->map(fn ($id) => $items->get($id))
            ->filter()
            ->values();
    }

    private function getOrderedHeaders(
        Request $request,
        string $criteriaKey,
        Builder $baseQuery,
        ?Collection $pageRecords = null
    ): array {
        return Cache::remember(
            "user_business_headers:{$criteriaKey}",
            self::SEARCH_CACHE_TTL,
            function () use ($baseQuery, $pageRecords) {
                $headers = [];

                if ($pageRecords instanceof Collection && $pageRecords->isNotEmpty()) {
                    $firstRecord = $pageRecords->first();

                    if (is_array($firstRecord->data_json)) {
                        $headers = array_keys($firstRecord->data_json);
                    }
                }

                if (empty($headers)) {
                    $record = (clone $baseQuery)->first();

                    if ($record && is_array($record->data_json)) {
                        $headers = array_keys($record->data_json);
                    }
                }

                return $this->orderHeaders($headers);
            }
        );
    }

    private function resolveVisibleColumns(Request $request, array $headers): array
    {
        $requested = $request->input('columns', []);

        if (! is_array($requested) || empty($requested)) {
            return $headers;
        }

        return array_values(array_filter(
            $headers,
            fn ($header) => in_array($header, $requested, true)
        ));
    }

    private function orderHeaders(array $headers): array
    {
        $orderedPriority = [
            'business_name',
            'executive_info',
            'executive_title',
            'executive_gender',
            'phone',
            'address',
            'city',
            'state',
            'zip',
            'sic_code',
            'sic_description',
            'email',
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
            if (! in_array($header, $orderedHeaders, true)) {
                $orderedHeaders[] = $header;
            }
        }

        return $orderedHeaders;
    }

    private function activeFilters(Request $request, ?State $selectedState): array
    {
        return [
            'Business Name' => $request->business_name,
            'Executive First Name' => $request->executive_first_name,
            'Executive Last Name' => $request->executive_last_name,
            'State' => $selectedState?->name,
            'City' => $request->city,
            'Address' => $request->address,
            'ZIP Code' => $request->zip_code,
            'Phone Number' => $request->phone_number,
        ];
    }

    private function countFilledJsonField(Builder $query, array $possibleKeys, bool $mustContainAt = false): int
    {
        $driver = DB::connection()->getDriverName();

        $countQuery = clone $query;
        $countQuery->reorder();

        return $countQuery->where(function (Builder $innerQuery) use ($driver, $possibleKeys, $mustContainAt) {
            if ($driver === 'mysql') {
                $expr = $this->jsonCoalesceExpression($possibleKeys);

                if ($mustContainAt) {
                    $innerQuery->whereRaw("COALESCE({$expr}, '') LIKE '%@%'");
                } else {
                    $innerQuery->whereRaw("NULLIF(TRIM(COALESCE({$expr}, '')), '') IS NOT NULL");
                }

                return;
            }

            if ($mustContainAt) {
                $innerQuery->where('records.data_json', 'like', '%@%');
            } else {
                $innerQuery->where('records.data_json', 'like', '%Address%');
            }
        })->count('records.id');
    }

    private function topJsonValues(Builder $query, array $possibleKeys, int $limit = 8): Collection
    {
        $driver = DB::connection()->getDriverName();

        if ($driver !== 'mysql') {
            return collect();
        }

        $expr = $this->jsonCoalesceExpression($possibleKeys);
        $labelExpr = "COALESCE({$expr}, 'N/A')";

        $aggregateQuery = clone $query;

        /*
        * IMPORTANT:
        * Remove inherited order/select from base query.
        * Otherwise ONLY_FULL_GROUP_BY fails because records.id stays in SELECT/ORDER BY.
        */
        $aggregateQuery->reorder();
        $aggregateQuery->getQuery()->columns = null;

        return $aggregateQuery
            ->selectRaw("{$labelExpr} as label, COUNT(*) as total")
            ->groupByRaw($labelExpr)
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
    }

    private function jsonCoalesceExpression(array $possibleKeys): string
    {
        $pieces = [];

        foreach ($possibleKeys as $key) {
            $jsonPath = '$."' . str_replace('"', '\"', $key) . '"';
            $pieces[] = "NULLIF(JSON_UNQUOTE(JSON_EXTRACT(records.data_json, '{$jsonPath}')), '')";
        }

        return 'COALESCE(' . implode(', ', $pieces) . ')';
    }

    private function headerLabel(string $header): string
    {
        $normalized = strtolower(trim(str_replace(['-', ' '], '_', $header)));

        return $this->columnAliases()[$normalized] ?? strtoupper($header);
    }

    private function columnAliases(): array
    {
        return [
            'business_name' => 'BUSINESS NAME',
            'executive_info' => 'EXECUTIVE INFO',
            'executive_title' => 'EXECUTIVE TITLE',
            'executive_gender' => 'EXECUTIVE GENDER',
            'phone' => 'PHONE',
            'phone_number' => 'PHONE',
            'address' => 'ADDRESS',
            'city' => 'CITY',
            'state' => 'STATE',
            'zip' => 'ZIP',
            'zip_code' => 'ZIP',
            'sic_code' => 'SIC CODE',
            'sic_description' => 'SIC DESCRIPTION',
            'email' => 'EMAIL',
        ];
    }

    private function criteriaCacheKey(Request $request): string
    {
        $payload = [
            'business_name' => trim((string) $request->business_name),
            'executive_first_name' => trim((string) $request->executive_first_name),
            'executive_last_name' => trim((string) $request->executive_last_name),
            'state_id' => $request->state_id,
            'city' => trim((string) $request->city),
            'address' => trim((string) $request->address),
            'zip_code' => trim((string) $request->zip_code),
            'phone_number' => trim((string) $request->phone_number),
        ];

        ksort($payload);

        return md5(json_encode($payload));
    }
}