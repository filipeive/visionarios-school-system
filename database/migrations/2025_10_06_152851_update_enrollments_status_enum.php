<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Esta migração usa sintaxe MySQL. Em SQLite (testes) ela deve ser ignorada.
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE enrollments MODIFY COLUMN status ENUM('active', 'pending', 'inactive', 'transferred', 'cancelled') NOT NULL DEFAULT 'pending'");
    }

    public function down()
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE enrollments MODIFY COLUMN status ENUM('active', 'inactive', 'transferred', 'cancelled') NOT NULL DEFAULT 'active'");
    }
};
