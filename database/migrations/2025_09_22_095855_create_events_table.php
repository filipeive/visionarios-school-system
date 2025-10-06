<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->text('description')->nullable();
            $table->date('event_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->enum('target_audience', ['all', 'teachers', 'parents', 'students', 'staff'])->default('all');
            $table->enum('type', ['meeting', 'exam', 'holiday', 'activity', 'celebration', 'other'])->default('other');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->boolean('send_notification')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
};
