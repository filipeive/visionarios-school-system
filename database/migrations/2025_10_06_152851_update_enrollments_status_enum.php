<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Para MySQL
        DB::statement("ALTER TABLE enrollments MODIFY COLUMN status ENUM('active', 'pending', 'inactive', 'transferred', 'cancelled') NOT NULL DEFAULT 'pending'");
        
        // Ou para outros bancos, usar Schema::table
         Schema::table('enrollments', function (Blueprint $table) {
            $table->enum('status', ['active', 'pending', 'inactive', 'transferred', 'cancelled'])
                  ->default('pending')
                   ->change();
        });
    }

    public function down()
    {
        DB::statement("ALTER TABLE enrollments MODIFY COLUMN status ENUM('active', 'inactive', 'transferred', 'cancelled') NOT NULL DEFAULT 'active'");
        
        // Ou para outros bancos
        Schema::table('enrollments', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive', 'transferred', 'cancelled'])
                  ->default('active')
                 ->change();
     });
    }
};