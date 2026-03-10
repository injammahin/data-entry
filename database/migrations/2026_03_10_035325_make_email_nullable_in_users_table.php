<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY username VARCHAR(255) NULL");
        DB::statement("ALTER TABLE users MODIFY email VARCHAR(255) NULL");
        DB::statement("ALTER TABLE users MODIFY role VARCHAR(255) NOT NULL DEFAULT 'user'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY username VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE users MODIFY email VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE users MODIFY role VARCHAR(255) NOT NULL DEFAULT 'admin'");
    }
};