<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Adicionar colunas que podem estar faltando
        
        if (!Schema::hasColumn('payments', 'reference_number')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->string('reference_number', 50)->unique()->after('id');
            });
        }

        if (!Schema::hasColumn('students', 'student_number')) {
            Schema::table('students', function (Blueprint $table) {
                $table->string('student_number', 20)->unique()->after('id');
            });
        }

        if (!Schema::hasColumn('teachers', 'bi_number')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->string('bi_number', 20)->unique()->nullable()->after('phone');
                $table->date('birth_date')->nullable()->after('bi_number');
                $table->enum('gender', ['male', 'female'])->nullable()->after('birth_date');
                $table->string('address')->nullable()->after('gender');
                $table->decimal('salary', 10, 2)->nullable()->after('address');
            });
        }
    }

    public function down()
    {
        // Rollback se necessário
        if (Schema::hasColumn('payments', 'reference_number')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropColumn('reference_number');
            });
        }
        if (Schema::hasColumn('students', 'student_number')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropColumn('student_number');
            });
        }
        if (Schema::hasColumn('teachers', 'bi_number')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->dropColumn(['bi_number', 'birth_date', 'gender', 'address', 'salary']);
            });
        }
    }
};
