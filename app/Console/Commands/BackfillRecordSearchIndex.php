<?php

namespace App\Console\Commands;

use App\Models\Record;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BackfillRecordSearchIndex extends Command
{
    protected $signature = 'records:build-search-index {--chunk=5000} {--from-id=0}';

    protected $description = 'Build or rebuild the fast search index table from records.data_json';

    public function handle(): int
    {
        $chunk = max(500, (int) $this->option('chunk'));
        $fromId = max(0, (int) $this->option('from-id'));

        $this->info("Building search index from records table...");
        $this->info("Chunk size: {$chunk}");
        $this->info("Starting from ID: {$fromId}");

        $processed = 0;

        Record::query()
            ->select(['id', 'state_id', 'data_json'])
            ->when($fromId > 0, fn ($q) => $q->where('id', '>', $fromId))
            ->orderBy('id')
            ->chunkById($chunk, function ($records) use (&$processed) {
                $payload = [];

                foreach ($records as $record) {
                    $data = is_array($record->data_json)
                        ? $record->data_json
                        : (json_decode($record->getRawOriginal('data_json') ?: '[]', true) ?: []);

                    $realEmail = $this->extractActualEmail($data);
                    $hashEmail = $this->extractEmailHash($data);

                    $businessName = $this->firstNonEmpty($data, [
                        'business_name',
                        'Business Name',
                        'BUSINESS NAME',
                        'company_name',
                        'Company Name',
                    ]);

                    $execFirst = $this->extractExecutiveFirstName($data);
                    $execLast = $this->extractExecutiveLastName($data);

                    $payload[] = [
    'record_id' => $record->id,
    'state_id' => $record->state_id,
    'business_name_norm' => $this->normalizeText($businessName, 191),
    'executive_first_name_norm' => $this->normalizeText($execFirst, 120),
    'executive_last_name_norm' => $this->normalizeText($execLast, 120),
    'executive_title_norm' => $this->normalizeText($this->firstNonEmpty($data, [
        'executive_title', 'Executive Title', 'EXECUTIVE TITLE',
    ]), 191),
    'city_norm' => $this->normalizeText($this->firstNonEmpty($data, [
        'city', 'City', 'CITY',
    ]), 120),
    'address_norm' => $this->normalizeText($this->firstNonEmpty($data, [
        'address', 'Address', 'ADDRESS', 'street_address', 'Street Address', 'mailing_address', 'Mailing Address',
    ]), 191),
    'zip_code_norm' => $this->normalizeZip($this->firstNonEmpty($data, [
        'zip', 'Zip', 'ZIP', 'zip_code', 'Zip Code', 'ZIP CODE', 'postal_code', 'Postal Code',
    ]), 32),
    'phone_norm' => $this->normalizePhone($this->firstNonEmpty($data, [
        'phone', 'Phone', 'PHONE', 'phone_number', 'Phone Number', 'PHONE NUMBER', 'telephone', 'Telephone',
    ]), 32),
    'sic_description_norm' => $this->normalizeText($this->firstNonEmpty($data, [
        'sic_description', 'SIC Description', 'SIC DESCRIPTION',
    ]), 191),
    'has_email' => (bool) ($realEmail || $hashEmail),
    'has_real_email' => (bool) $realEmail,
    'has_hashed_email' => (bool) $hashEmail,
    'has_direct_mail' => (bool) $this->normalizeText($this->firstNonEmpty($data, [
        'address', 'Address', 'ADDRESS', 'street_address', 'Street Address', 'mailing_address', 'Mailing Address',
    ]), 191),
    'created_at' => now(),
    'updated_at' => now(),
];
                }

                DB::table('record_search_indexes')->upsert(
                    $payload,
                    ['record_id'],
                    [
                        'state_id',
                        'business_name_norm',
                        'executive_first_name_norm',
                        'executive_last_name_norm',
                        'executive_title_norm',
                        'city_norm',
                        'address_norm',
                        'zip_code_norm',
                        'phone_norm',
                        'sic_description_norm',
                        'has_email',
                        'has_real_email',
                        'has_hashed_email',
                        'has_direct_mail',
                        'updated_at',
                    ]
                );

                $processed += count($payload);
                $this->info("Indexed: {$processed}");
            }, 'id');

        $this->info('Search index build completed.');

        return self::SUCCESS;
    }

    private function firstNonEmpty(array $data, array $keys): ?string
    {
        foreach ($keys as $key) {
            $value = $data[$key] ?? null;

            if (is_string($value) && trim($value) !== '') {
                return trim($value);
            }
        }

        return null;
    }

    private function extractExecutiveFirstName(array $data): ?string
    {
        return $this->firstNonEmpty($data, [
            'executive_first_name',
            'Executive First Name',
            'first_name',
            'First Name',
            'owner_first_name',
            'Owner First Name',
        ]);
    }

    private function extractExecutiveLastName(array $data): ?string
    {
        return $this->firstNonEmpty($data, [
            'executive_last_name',
            'Executive Last Name',
            'last_name',
            'Last Name',
            'owner_last_name',
            'Owner Last Name',
        ]);
    }

private function normalizeText(?string $value, int $maxLength = 191): ?string
{
    if (! is_string($value) || trim($value) === '') {
        return null;
    }

    $value = mb_strtolower(trim($value));
    $value = preg_replace('/[^\pL\pN]+/u', ' ', $value);
    $value = preg_replace('/\s+/u', ' ', $value);
    $value = trim($value);

    if ($value === '') {
        return null;
    }

    return mb_substr($value, 0, $maxLength);
}

private function normalizePhone(?string $value, int $maxLength = 32): ?string
{
    if (! is_string($value) || trim($value) === '') {
        return null;
    }

    $digits = preg_replace('/\D+/', '', $value);

    if ($digits === '') {
        return null;
    }

    return substr($digits, 0, $maxLength);
}

private function normalizeZip(?string $value, int $maxLength = 32): ?string
{
    if (! is_string($value) || trim($value) === '') {
        return null;
    }

    $value = strtoupper(trim($value));
    $value = preg_replace('/\s+/', '', $value);

    if ($value === '') {
        return null;
    }

    return substr($value, 0, $maxLength);
}

    private function extractActualEmail(array $data): ?string
    {
        $keys = [
            'email', 'Email', 'EMAIL',
            'email_address', 'Email Address', 'Email_Address',
        ];

        foreach ($keys as $key) {
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
        $keys = [
            'Email_Hash', 'email_hash', 'emailHash', 'EMAIL_HASH',
        ];

        foreach ($keys as $key) {
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
}