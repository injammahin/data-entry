<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('imports', function (Blueprint $table) {
            $table->unsignedBigInteger('skipped_rows')->default(0)->after('processed_rows');
            $table->unsignedBigInteger('failed_rows')->default(0)->after('skipped_rows');
            $table->timestamp('started_at')->nullable()->after('error_message');
            $table->timestamp('completed_at')->nullable()->after('started_at');
            $table->string('file_hash', 64)->nullable()->after('original_name')->index();
        });
    }

    public function down(): void
    {
        Schema::table('imports', function (Blueprint $table) {
            $table->dropColumn([
                'skipped_rows',
                'failed_rows',
                'started_at',
                'completed_at',
                'file_hash',
            ]);
        });
    }
};