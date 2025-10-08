<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('class_schedules', function (Blueprint $table) {
            $table->id();
            
            // Usar a tabela existente 'classes' com o modelo ClassRoom
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            
            $table->tinyInteger('weekday')->comment('0=Domingo, 1=Segunda, ..., 6=Sábado');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('classroom', 50)->nullable();
            $table->integer('academic_year');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Índices
            $table->index(['teacher_id', 'weekday']);
            $table->index(['class_id', 'weekday']);
            $table->index(['academic_year', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('class_schedules');
    }
};