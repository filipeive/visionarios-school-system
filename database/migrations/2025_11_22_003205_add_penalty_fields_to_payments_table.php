<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'penalty_percentage')) {
                $table->decimal('penalty_percentage', 5, 2)->default(0)->after('penalty');
            }
            if (!Schema::hasColumn('payments', 'penalty_applied_at')) {
                $table->timestamp('penalty_applied_at')->nullable()->after('penalty_percentage');
            }
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['penalty_percentage', 'penalty_applied_at']);
        });
    }
};