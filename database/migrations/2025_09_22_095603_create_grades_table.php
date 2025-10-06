<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->decimal('grade', 4, 2);
            $table->enum('assessment_type', ['continuous', 'test', 'exam', 'final'])->default('continuous');
            $table->integer('term'); // Trimestre (1, 2, 3)
            $table->integer('year')->default(date('Y'));
            $table->date('date_recorded');
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('grades');
    }
};
