<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_number', 20)->unique();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->date('birthdate');
            $table->string('birth_place', 50)->nullable();
            $table->date('registration_date');
            $table->decimal('monthly_fee', 10, 2);
            $table->foreignId('parent_id')->nullable()->constrained('parents', 'user_id')->onDelete('set null');
            $table->string('address')->nullable();
            $table->string('school_exit')->nullable();
            $table->integer('exit_year')->nullable();
            $table->string('previous_class', 50)->nullable();
            $table->string('previous_grade', 50)->nullable();
            $table->string('emergency_contact', 100)->nullable();
            $table->string('emergency_phone', 20)->nullable();
            $table->string('medical_certificate')->nullable();
            $table->string('passport_photo')->nullable();
            $table->text('observations')->nullable();
            $table->boolean('has_special_needs')->default(false);
            $table->text('special_needs_description')->nullable();
            $table->enum('status', ['active', 'inactive', 'transferred', 'graduated'])->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('students');
    }
};