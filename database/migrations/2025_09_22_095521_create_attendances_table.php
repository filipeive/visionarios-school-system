<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->date('attendance_date');
            $table->enum('status', ['present', 'absent', 'late', 'excused'])->default('present');
            $table->text('notes')->nullable();
            $table->foreignId('marked_by')->constrained('users')->onDelete('cascade');
            $table->time('arrival_time')->nullable();
            $table->timestamps();
            
            $table->unique(['student_id', 'attendance_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendances');
    }
};