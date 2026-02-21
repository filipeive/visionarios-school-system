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
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE assessments MODIFY COLUMN type ENUM('test', 'assignment', 'project', 'exam', 'participation', 'continuous', 'ACS1', 'ACS2', 'ACS3', 'ACP', 'ACF', 'behavioral') NOT NULL DEFAULT 'test'");
        }

        Schema::table('assessments', function (Blueprint $table) {
            if (!Schema::hasColumn('assessments', 'term')) {
                $table->integer('term')->nullable()->after('type'); // 1, 2, 3
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('assessments', 'term')) {
            Schema::table('assessments', function (Blueprint $table) {
                $table->dropColumn('term');
            });
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE assessments MODIFY COLUMN type ENUM('test', 'assignment', 'project', 'exam', 'participation', 'continuous') NOT NULL DEFAULT 'test'");
        }
    }
};
