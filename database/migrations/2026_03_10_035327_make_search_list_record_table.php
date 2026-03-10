<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('search_list_record', function (Blueprint $table) {
            $table->id();
            $table->foreignId('search_list_id')->constrained()->cascadeOnDelete();
            $table->foreignId('record_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['search_list_id', 'record_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_list_record');
    }
};