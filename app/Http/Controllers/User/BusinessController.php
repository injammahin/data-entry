<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Record;
use App\Models\RecordSearchIndex;
use App\Models\SearchList;
use App\Models\State;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Excel as ExcelWriter;
use Maatwebsite\Excel\Facades\Excel;

class BusinessController extends Controller
{
    private const STATES_CACHE_TTL = 86400;
    private const SEARCH_CACHE_TTL = 600;
    private const SNAPSHOT_MAX_RECORDS = 10000;
    private const CACHE_VERSION = 'v4';

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

        $context = $this->buildPageContext($request, 30, false);

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
        $indexQuery = $this->buildFilteredIndexQuery($request);

        $insights = Cache::remember(
            "user_business_insights:{$criteriaKey}:" . self::CACHE_VERSION,
            self::SEARCH_CACHE_TTL,
            function () use ($indexQuery) {
                return [
                    'total_results' => (clone $indexQuery)->reorder()->count('record_search_indexes.record_id'),
                    'state_count' => (clone $indexQuery)->reorder()
                        ->whereNotNull('record_search_indexes.state_id')
                        ->distinct('record_search_indexes.state_id')
                        ->count('record_search_indexes.state_id'),
                    'email_count' => (clone $indexQuery)->reorder()->where('record_search_indexes.has_email', true)->count(),
                    'real_email_count' => (clone $indexQuery)->reorder()->where('record_search_indexes.has_real_email', true)->count(),
                    'hashed_email_count' => (clone $indexQuery)->reorder()->where('record_search_indexes.has_hashed_email', true)->count(),
                    'direct_mail_count' => (clone $indexQuery)->reorder()->where('record_search_indexes.has_direct_mail', true)->count(),
                    'top_cities' => $this->topIndexedValues(clone $indexQuery, 'record_search_indexes.city_norm', 8),
                    'top_sic_descriptions' => $this->topIndexedValues(clone $indexQuery, 'record_search_indexes.sic_description_norm', 8),
                    'top_titles' => $this->topIndexedValues(clone $indexQuery, 'record_search_indexes.executive_title_norm', 8),
                ];
            }
        );

        $context = $this->buildPageContext($request, 30, true, [
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

        $context = $this->buildPageContext($request, 12, false);

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

        $context = $this->buildPageContext($request, 12, false);

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
        $indexQuery = $this->buildFilteredIndexQuery($request);

        $totalRecords = $this->resolveTotalRecords($criteriaKey, $indexQuery);

        if ($totalRecords === 0) {
            return back()->with('error', 'No records found to save.');
        }

        $sampleIds = (clone $indexQuery)
            ->reorder()
            ->limit(5)
            ->pluck('record_search_indexes.record_id')
            ->all();

        $sampleRecords = $this->fetchRecordsByIds($sampleIds);
        $headers = $this->getOrderedHeaders($sampleRecords);
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

        if ($totalRecords <= self::SNAPSHOT_MAX_RECORDS) {
            (clone $indexQuery)
                ->reorder()
                ->chunkById(1000, function ($rows) use ($searchList) {
                    $payload = [];

                    foreach ($rows as $row) {
                        $payload[] = [
                            'search_list_id' => $searchList->id,
                            'record_id' => $row->record_id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }

                    if (! empty($payload)) {
                        DB::table('search_list_record')->insertOrIgnore($payload);
                    }
                }, 'record_id');
        }

        return back()->with(
            'success',
            $totalRecords <= self::SNAPSHOT_MAX_RECORDS
                ? 'Search list saved successfully.'
                : 'Search list saved successfully. Large result set stored in smart mode.'
        );
    }

    public function exportCsv(Request $request)
    {
        return $this->exportFile($request, ExcelWriter::CSV, 'business-search-results.csv');
    }

    public function exportXlsx(Request $request)
    {
        return $this->exportFile($request, ExcelWriter::XLSX, 'business-search-results.xlsx');
    }

    private function exportFile(Request $request, string $writerType, string $filename)
    {
        $this->validateSearchRequest($request);

        if ($redirect = $this->ensureHasFilters($request)) {
            return $redirect;
        }

        $baseQuery = $this->buildFilteredRecordQuery($request);
        $headers = $this->getOrderedHeaders();
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

                $actualEmail = $this->extractActualEmail($data);
                $emailHash = $this->extractEmailHash($data);

                $data['Email'] = $actualEmail ?? '';
                $data['Email_Hash'] = $emailHash ?? '';
                $data['Email_Status'] = $actualEmail
                    ? 'REAL EMAIL'
                    : ($emailHash ? 'HASHED EMAIL ONLY' : '');

                $row = [];

                foreach ($this->visibleColumns as $column) {
                    $value = $data[$column] ?? '';

                    $row[] = is_array($value)
                        ? json_encode($value)
                        : preg_replace("/\r\n|\r|\n/", ' ', (string) $value);
                }

                return $row;
            }

            private function extractActualEmail(array $data): ?string
            {
                foreach (['email', 'Email', 'EMAIL', 'email_address', 'Email Address', 'Email_Address'] as $key) {
                    $value = $data[$key] ?? null;

                    if (is_string($value) && str_contains($value, '@')) {
                        return trim($value);
                    }
                }

                if (! empty($data['contacts']) && is_array($data['contacts'])) {
                    foreach ($data['contacts'] as $contact) {
                        if (! is_array($contact)) {
                            continue;
                        }

                        foreach (['email', 'Email', 'emailAddress', 'email_address'] as $key) {
                            $value = $contact[$key] ?? null;

                            if (is_string($value) && str_contains($value, '@')) {
                                return trim($value);
                            }
                        }
                    }
                }

                return null;
            }

            private function extractEmailHash(array $data): ?string
            {
                foreach (['Email_Hash', 'email_hash', 'emailHash', 'EMAIL_HASH'] as $key) {
                    $value = $data[$key] ?? null;

                    if (is_string($value) && trim($value) !== '') {
                        return trim($value);
                    }
                }

                if (! empty($data['contacts']) && is_array($data['contacts'])) {
                    foreach ($data['contacts'] as $contact) {
                        if (! is_array($contact)) {
                            continue;
                        }

                        foreach (['emailHash', 'email_hash', 'Email_Hash'] as $key) {
                            $value = $contact[$key] ?? null;

                            if (is_string($value) && trim($value) !== '') {
                                return trim($value);
                            }
                        }
                    }
                }

                return null;
            }
        };

        return Excel::download($export, $filename, $writerType);
    }

    private function buildPageContext(
        Request $request,
        int $defaultPerPage,
        bool $loadMetrics = false,
        ?array $metricsOverride = null
    ): array {
        $perPage = (int) $request->get('per_page', $defaultPerPage);
        $allowed = [12, 30, 50, 100];

        if (! in_array($perPage, $allowed, true)) {
            $perPage = $defaultPerPage;
        }

        $page = max(1, (int) $request->get('page', 1));
        $criteriaKey = $this->criteriaCacheKey($request);
        $indexQuery = $this->buildFilteredIndexQuery($request);

        $totalRecords = $this->resolveTotalRecords($criteriaKey, $indexQuery);

        $paginator = (clone $indexQuery)
            ->simplePaginate($perPage, ['record_search_indexes.record_id'], 'page', $page)
            ->withQueryString();

        $pageIds = collect($paginator->items())->pluck('record_id')->all();
        $pageRecords = $this->fetchRecordsByIds($pageIds);
        $paginator->setCollection($pageRecords);

        // stale total cache recovery
        if ($totalRecords === 0 && $pageRecords->isNotEmpty()) {
            $totalRecords = (clone $indexQuery)->reorder()->count('record_search_indexes.record_id');

            Cache::put(
                "user_business_total:{$criteriaKey}:" . self::CACHE_VERSION,
                $totalRecords,
                self::SEARCH_CACHE_TTL
            );
        }

        $headers = $this->getOrderedHeaders($pageRecords);
        $visibleColumns = $this->resolveVisibleColumns($request, $headers);

        $selectedState = null;
        if ($request->filled('state_id')) {
            $selectedState = Cache::remember(
                'user_business_state_' . $request->state_id,
                self::STATES_CACHE_TTL,
                fn () => State::select('id', 'name')->find($request->state_id)
            );
        }

        if ($metricsOverride !== null) {
            $metrics = [
                'emailCount' => (int) ($metricsOverride['emailCount'] ?? 0),
                'directMailCount' => (int) ($metricsOverride['directMailCount'] ?? 0),
            ];
        } elseif ($loadMetrics) {
            $metrics = Cache::remember(
                "user_business_metrics:{$criteriaKey}:" . self::CACHE_VERSION,
                self::SEARCH_CACHE_TTL,
                fn () => [
                    'emailCount' => (clone $indexQuery)->reorder()->where('record_search_indexes.has_email', true)->count(),
                    'directMailCount' => (clone $indexQuery)->reorder()->where('record_search_indexes.has_direct_mail', true)->count(),
                ]
            );
        } else {
            $metrics = [
                'emailCount' => 0,
                'directMailCount' => 0,
            ];
        }

        return [
            'records' => $paginator,
            'totalRecords' => $totalRecords,
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

    private function resolveTotalRecords(string $criteriaKey, Builder $indexQuery): int
    {
        return (int) Cache::remember(
            "user_business_total:{$criteriaKey}:" . self::CACHE_VERSION,
            self::SEARCH_CACHE_TTL,
            fn () => (clone $indexQuery)->reorder()->count('record_search_indexes.record_id')
        );
    }

    private function buildFilteredIndexQuery(Request $request): Builder
    {
        $query = RecordSearchIndex::query()
            ->select(['record_search_indexes.record_id'])
            ->orderBy('record_search_indexes.record_id');

        if ($request->filled('state_id')) {
            $query->where('record_search_indexes.state_id', (int) $request->state_id);
        }

        $this->applyPrefixTextFilter($query, 'record_search_indexes.business_name_norm', $request->business_name);
        $this->applyPrefixTextFilter($query, 'record_search_indexes.executive_first_name_norm', $request->executive_first_name);
        $this->applyPrefixTextFilter($query, 'record_search_indexes.executive_last_name_norm', $request->executive_last_name);
        $this->applyPrefixTextFilter($query, 'record_search_indexes.city_norm', $request->city);
        $this->applyPrefixTextFilter($query, 'record_search_indexes.address_norm', $request->address);
        $this->applyZipFilter($query, 'record_search_indexes.zip_code_norm', $request->zip_code);
        $this->applyPhoneFilter($query, 'record_search_indexes.phone_norm', $request->phone_number);

        return $query;
    }

    private function buildFilteredRecordQuery(Request $request): Builder
    {
        $subQuery = (clone $this->buildFilteredIndexQuery($request))
            ->reorder()
            ->select('record_search_indexes.record_id');

        return Record::query()
            ->joinSub($subQuery, 'idx', function ($join) {
                $join->on('idx.record_id', '=', 'records.id');
            })
            ->select([
                'records.id',
                'records.state_id',
                'records.import_id',
                'records.row_number',
                'records.data_json',
            ])
            ->orderBy('records.id');
    }

    private function applyPrefixTextFilter(Builder $query, string $column, ?string $value): void
    {
        $value = $this->normalizeText($value);

        if ($value === null) {
            return;
        }

        $query->where($column, 'like', $value . '%');
    }

    private function applyPhoneFilter(Builder $query, string $column, ?string $value): void
    {
        $value = $this->normalizePhone($value);

        if ($value === null) {
            return;
        }

        $query->where($column, 'like', $value . '%');
    }

    private function applyZipFilter(Builder $query, string $column, ?string $value): void
    {
        $value = $this->normalizeZip($value);

        if ($value === null) {
            return;
        }

        $query->where($column, 'like', $value . '%');
    }

    private function topIndexedValues(Builder $query, string $column, int $limit = 8): Collection
    {
        return (clone $query)
            ->reorder()
            ->whereNotNull($column)
            ->where($column, '!=', '')
            ->selectRaw("{$column} as label, COUNT(*) as total")
            ->groupBy($column)
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
    }

    private function validateSearchRequest(Request $request): void
    {
        $request->validate([
            'business_name' => ['nullable', 'string', 'min:2', 'max:255'],
            'executive_first_name' => ['nullable', 'string', 'min:2', 'max:255'],
            'executive_last_name' => ['nullable', 'string', 'min:2', 'max:255'],
            'state_id' => ['nullable', 'integer', 'exists:states,id'],
            'city' => ['nullable', 'string', 'min:2', 'max:255'],
            'address' => ['nullable', 'string', 'min:2', 'max:255'],
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

    private function fetchRecordsByIds(array $ids): Collection
    {
        if (empty($ids)) {
            return collect();
        }

        $records = Record::query()
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
            ->map(fn ($record) => $this->enrichRecordData($record))
            ->keyBy('id');

        return collect($ids)
            ->map(fn ($id) => $records->get($id))
            ->filter()
            ->values();
    }

    private function enrichRecordData(Record $record): Record
    {
        $data = is_array($record->data_json)
            ? $record->data_json
            : (json_decode($record->getRawOriginal('data_json') ?: '[]', true) ?: []);

        $actualEmail = $this->extractActualEmail($data);
        $emailHash = $this->extractEmailHash($data);

        $data['Email'] = $actualEmail ?? '';
        $data['Email_Hash'] = $emailHash ?? '';
        $data['Email_Status'] = $actualEmail
            ? 'REAL EMAIL'
            : ($emailHash ? 'HASHED EMAIL ONLY' : '');

        $record->setAttribute('data_json', $data);

        return $record;
    }

    private function getOrderedHeaders(?Collection $pageRecords = null): array
    {
        $headers = [];

        if ($pageRecords instanceof Collection && $pageRecords->isNotEmpty()) {
            $firstRecord = $pageRecords->first();

            if (is_array($firstRecord->data_json)) {
                $headers = array_keys($firstRecord->data_json);
            }
        }

        if (empty($headers)) {
            $headers = Cache::remember(
                'user_business_default_headers:' . self::CACHE_VERSION,
                3600,
                function () {
                    $record = Record::query()
                        ->select('data_json')
                        ->whereNotNull('data_json')
                        ->first();

                    if (! $record) {
                        return [];
                    }

                    $data = is_array($record->data_json)
                        ? $record->data_json
                        : (json_decode($record->getRawOriginal('data_json') ?: '[]', true) ?: []);

                    return array_keys($data);
                }
            );
        }

        foreach (['Email', 'Email_Hash', 'Email_Status'] as $virtualHeader) {
            if (! in_array($virtualHeader, $headers, true)) {
                $headers[] = $virtualHeader;
            }
        }

        return $this->orderHeaders($headers);
    }

    private function resolveVisibleColumns(Request $request, array $headers): array
    {
        $requested = $request->input('columns', []);

        if (! is_array($requested) || empty($requested)) {
            return array_values(array_filter($headers, function ($header) {
                $normalized = strtolower(trim(str_replace(['-', ' '], '_', $header)));
                return $normalized !== 'email_hash';
            }));
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
            'executive_first_name',
            'executive_last_name',
            'phone',
            'address',
            'city',
            'state',
            'zip',
            'county',
            'website',
            'primary_sic',
            'primary_sic_description',
            'sic_code',
            'sic_description',
            'employees',
            'sales_volume',
            'location_type',
            'infogroup_id',
            'msa',
            'latitude',
            'longitude',
            'email',
            'email_hash',
            'email_status',
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

    private function extractActualEmail(array $data): ?string
    {
        foreach ([
            'email', 'Email', 'EMAIL',
            'email_address', 'Email Address', 'Email_Address',
        ] as $key) {
            $value = $data[$key] ?? null;

            if (is_string($value) && str_contains($value, '@')) {
                return trim($value);
            }
        }

        if (! empty($data['contacts']) && is_array($data['contacts'])) {
            foreach ($data['contacts'] as $contact) {
                if (! is_array($contact)) {
                    continue;
                }

                foreach (['email', 'Email', 'emailAddress', 'email_address'] as $key) {
                    $value = $contact[$key] ?? null;

                    if (is_string($value) && str_contains($value, '@')) {
                        return trim($value);
                    }
                }
            }
        }

        return null;
    }

    private function extractEmailHash(array $data): ?string
    {
        foreach (['Email_Hash', 'email_hash', 'emailHash', 'EMAIL_HASH'] as $key) {
            $value = $data[$key] ?? null;

            if (is_string($value) && trim($value) !== '') {
                return trim($value);
            }
        }

        if (! empty($data['contacts']) && is_array($data['contacts'])) {
            foreach ($data['contacts'] as $contact) {
                if (! is_array($contact)) {
                    continue;
                }

                foreach (['emailHash', 'email_hash', 'Email_Hash'] as $key) {
                    $value = $contact[$key] ?? null;

                    if (is_string($value) && trim($value) !== '') {
                        return trim($value);
                    }
                }
            }
        }

        return null;
    }

    private function headerLabel(string $header): string
    {
        $normalized = strtolower(trim(str_replace(['-', ' '], '_', $header)));

        return $this->columnAliases()[$normalized] ?? strtoupper(str_replace('_', ' ', $header));
    }

    private function columnAliases(): array
    {
        return [
            'business_name' => 'BUSINESS NAME',
            'executive_info' => 'EXECUTIVE INFO',
            'executive_title' => 'EXECUTIVE TITLE',
            'executive_gender' => 'EXECUTIVE GENDER',
            'executive_first_name' => 'EXECUTIVE FIRST NAME',
            'executive_last_name' => 'EXECUTIVE LAST NAME',
            'phone' => 'PHONE',
            'phone_number' => 'PHONE',
            'address' => 'ADDRESS',
            'city' => 'CITY',
            'state' => 'STATE',
            'zip' => 'ZIP',
            'zip_code' => 'ZIP',
            'county' => 'COUNTY',
            'website' => 'WEBSITE',
            'primary_sic' => 'PRIMARY SIC',
            'primary_sic_description' => 'PRIMARY SIC DESCRIPTION',
            'sic_code' => 'SIC CODE',
            'sic_description' => 'SIC DESCRIPTION',
            'employees' => 'EMPLOYEES',
            'sales_volume' => 'SALES VOLUME',
            'location_type' => 'LOCATION TYPE',
            'infogroup_id' => 'INFOGROUP ID',
            'msa' => 'MSA',
            'latitude' => 'LATITUDE',
            'longitude' => 'LONGITUDE',
            'email' => 'EMAIL',
            'email_hash' => 'EMAIL HASH',
            'email_status' => 'EMAIL STATUS',
        ];
    }

    private function criteriaCacheKey(Request $request): string
    {
        $payload = [
            'business_name' => $this->normalizeText($request->business_name),
            'executive_first_name' => $this->normalizeText($request->executive_first_name),
            'executive_last_name' => $this->normalizeText($request->executive_last_name),
            'state_id' => $request->state_id ? (int) $request->state_id : null,
            'city' => $this->normalizeText($request->city),
            'address' => $this->normalizeText($request->address),
            'zip_code' => $this->normalizeZip($request->zip_code),
            'phone_number' => $this->normalizePhone($request->phone_number),
        ];

        ksort($payload);

        return md5(json_encode($payload));
    }

    private function normalizeText(?string $value): ?string
    {
        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        $value = mb_strtolower(trim($value));
        $value = preg_replace('/[^\pL\pN]+/u', ' ', $value);
        $value = preg_replace('/\s+/u', ' ', $value);
        $value = trim($value);

        return $value !== '' ? $value : null;
    }

    private function normalizePhone(?string $value): ?string
    {
        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $value);

        return $digits !== '' ? $digits : null;
    }

    private function normalizeZip(?string $value): ?string
    {
        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        $value = strtoupper(trim($value));
        $value = preg_replace('/\s+/', '', $value);

        return $value !== '' ? $value : null;
    }
}