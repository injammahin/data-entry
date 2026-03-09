<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('imports', function (Blueprint $table) {
            $table->unsignedBigInteger('successful_rows')->default(0)->after('processed_rows');
        });
    }

    public function down(): void
    {
        Schema::table('imports', function (Blueprint $table) {
            $table->dropColumn([
                'successful_rows',
                'skipped_rows',
                'started_at',
                'completed_at',
            ]);
        });
    }
};