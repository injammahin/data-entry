<?php

namespace App\Jobs;

use App\Models\Import;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProcessCsvImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 0;
    public int $tries = 1;

    public function __construct(public int $importId)
    {
    }

    public function handle(): void
    {
        $import = Import::findOrFail($this->importId);

        $import->update([
            'status' => 'processing',
            'error_message' => null,
            'started_at' => now(),
            'completed_at' => null,
            'processed_rows' => 0,
            'successful_rows' => 0,
            'skipped_rows' => 0,
            'total_rows' => 0,
        ]);

        $filePath = storage_path('app/' . $import->file_name);

        if (!file_exists($filePath)) {
            $import->update([
                'status' => 'failed',
                'error_message' => 'CSV file not found.',
                'completed_at' => now(),
            ]);
            return;
        }

        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            $import->update([
                'status' => 'failed',
                'error_message' => 'Unable to open CSV file.',
                'completed_at' => now(),
            ]);
            return;
        }

        $headers = fgetcsv($handle);
        if (!$headers || count($headers) === 0) {
            fclose($handle);
            $import->update([
                'status' => 'failed',
                'error_message' => 'CSV header row is missing or invalid.',
                'completed_at' => now(),
            ]);
            return;
        }

        $headers = $this->normalizeHeaders($headers);
        $import->update(['headers' => $headers]);

        $batch = [];
        $processed = 0;
        $successful = 0;
        $skipped = 0;
        $total = 0;
        $rowNumber = 1;
        $chunkSize = 1000; // Adjust the chunk size to optimize database performance

        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;
            $total++;
            $processed++;

            if ($this->isRowEmpty($row)) {
                $skipped++;
                continue;
            }

            if (count($row) !== count($headers)) {
                $skipped++;
                continue;
            }

            $combined = array_combine($headers, $row);
            if ($combined === false) {
                $skipped++;
                continue;
            }

            $batch[] = [
                'state_id' => $import->state_id,
                'import_id' => $import->id,
                'row_number' => $rowNumber,
                'data_json' => json_encode($combined, JSON_UNESCAPED_UNICODE),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($batch) >= $chunkSize) {
                DB::table('records')->insert($batch);
                $successful += count($batch);

                $import->update([
                    'processed_rows' => $processed,
                    'successful_rows' => $successful,
                    'skipped_rows' => $skipped,
                    'total_rows' => $total,
                ]);

                $batch = []; // Reset batch after inserting
            }
        }

        // Insert remaining records after loop
        if (!empty($batch)) {
            DB::table('records')->insert($batch);
            $successful += count($batch);
        }

        fclose($handle);

        $import->update([
            'processed_rows' => $processed,
            'successful_rows' => $successful,
            'skipped_rows' => $skipped,
            'total_rows' => $total,
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    protected function normalizeHeaders(array $headers): array
    {
        $cleaned = [];
        $used = [];
        foreach ($headers as $index => $header) {
            $header = trim((string) $header);
            if ($header === '') {
                $header = 'column_' . ($index + 1);
            }
            $header = preg_replace('/\s+/', '_', $header);
            $header = preg_replace('/[^A-Za-z0-9_\-]/', '', $header);
            if ($header === '') {
                $header = 'column_' . ($index + 1);
            }

            $base = $header;
            $counter = 2;
            while (in_array($header, $used, true)) {
                $header = $base . '_' . $counter;
                $counter++;
            }

            $used[] = $header;
            $cleaned[] = $header;
        }
        return $cleaned;
    }

    protected function isRowEmpty(array $row): bool
    {
        foreach ($row as $value) {
            if (trim((string) $value) !== '') {
                return false;
            }
        }
        return true;
    }

    public function failed(Throwable $exception): void
    {
        $import = Import::find($this->importId);
        if ($import) {
            $import->update([
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
                'completed_at' => now(),
            ]);
        }
    }
}