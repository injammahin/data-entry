<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->constrained()->cascadeOnDelete();
            $table->foreignId('import_id')->constrained('imports')->cascadeOnDelete();
            $table->unsignedBigInteger('row_number')->nullable();
            $table->json('data_json');
            $table->timestamps();

            $table->index('state_id');
            $table->index('import_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('records');
    }
};