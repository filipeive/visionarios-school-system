<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('parents', function (Blueprint $table) {
            $table->foreignId('user_id')->primary()->constrained('users')->onDelete('cascade');
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('address')->nullable();
            $table->string('mother_name', 100)->nullable();
            $table->string('mother_phone', 20)->nullable();
            $table->string('mother_email', 100)->nullable();
            $table->enum('relationship', ['Father', 'Mother', 'Uncle', 'Aunt', 'Grandfather', 'Grandmother', 'Brother', 'Sister', 'Other'])->nullable();
            $table->string('profession', 100)->nullable();
            $table->string('workplace', 100)->nullable();
            $table->string('emergency_contact', 100)->nullable();
            $table->string('emergency_phone', 20)->nullable();
            $table->string('bi_number', 20)->nullable();
            $table->date('birth_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('parents');
    }
};