<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('secretaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('full_name');
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('department')->default('administration');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('secretaries');
    }
};
