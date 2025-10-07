<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_guardians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('parent_id')->constrained('parents', 'user_id')->onDelete('cascade');
            $table->enum('relationship', ['father', 'mother', 'guardian', 'other'])->default('guardian');
            $table->boolean('is_primary')->default(false);
            $table->boolean('can_pickup')->default(true);
            $table->boolean('emergency_contact')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_guardians');
    }
};
