<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->integer('school_year');
            $table->enum('status', ['active', 'inactive', 'transferred', 'cancelled'])->default('active');
            $table->date('enrollment_date');
            $table->date('cancellation_date')->nullable();
            $table->decimal('monthly_fee', 10, 2);
            $table->integer('payment_day')->default(10); // Dia do mês para pagamento
            $table->text('observations')->nullable();
            $table->timestamps();
            
            $table->unique(['student_id', 'school_year']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('enrollments');
    }
};