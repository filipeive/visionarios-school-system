<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->foreignId('assessment_id')->nullable()->after('subject_id')
                ->constrained()->onDelete('cascade');
            
            // Adicionar índice composto para evitar duplicatas
            $table->unique(['student_id', 'assessment_id'], 'unique_student_assessment');
        });
    }

    /**
     * Reverse the migrations.
     */
   public function down()
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->dropForeign(['assessment_id']);
            $table->dropUnique('unique_student_assessment');
            $table->dropColumn('assessment_id');
        });
    }
};
