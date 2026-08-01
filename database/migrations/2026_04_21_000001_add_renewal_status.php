<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        // Adicionar pending_renewal aos status dos alunos
        DB::statement("ALTER TABLE students MODIFY COLUMN status ENUM('active', 'inactive', 'transferred', 'graduated', 'pending_renewal') DEFAULT 'active'");

        // Adicionar pending aos status das matrículas
        DB::statement("ALTER TABLE enrollments MODIFY COLUMN status ENUM('active', 'completed', 'cancelled', 'transferred', 'suspended', 'pending') DEFAULT 'active'");
    }

    public function down()
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        // Remover valores
        DB::statement("ALTER TABLE students MODIFY COLUMN status ENUM('active', 'inactive', 'transferred', 'graduated') DEFAULT 'active'");
        DB::statement("ALTER TABLE enrollments MODIFY COLUMN status ENUM('active', 'completed', 'cancelled', 'transferred', 'suspended') DEFAULT 'active'");
    }
};
