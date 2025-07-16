<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE purchases MODIFY COLUMN status ENUM('pending', 'paid', 'overdue') DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE purchases MODIFY COLUMN status ENUM('pending', 'paid') DEFAULT 'pending'");
    }
};
