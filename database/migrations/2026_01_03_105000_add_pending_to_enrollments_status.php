<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // MySQL doesn't support modifying enum directly via Blueprint easily without doctrine/dbal
        // and even then it's tricky. Using raw SQL is safer for enums.
        DB::statement("ALTER TABLE enrollments MODIFY COLUMN status ENUM('active', 'pending', 'inactive', 'transferred', 'cancelled') DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE enrollments MODIFY COLUMN status ENUM('active', 'inactive', 'transferred', 'cancelled') DEFAULT 'active'");
    }
};
