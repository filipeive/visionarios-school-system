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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['test', 'assignment', 'project', 'exam', 'participation', 'continuous']);
            $table->decimal('max_grade', 5, 2)->default(20.00);
            $table->decimal('weight', 5, 2)->default(1.00); // Peso na média
            $table->date('due_date');
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            
            $table->index(['class_id', 'due_date']);
            $table->index(['teacher_id', 'due_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
