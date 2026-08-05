<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gate_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('class_id')->nullable()->constrained('classes')->onDelete('set null');
            $table->string('action', 10); // 'entry' or 'exit'
            $table->timestamp('logged_at');
            $table->string('method', 20)->default('manual'); // 'manual', 'qr', 'barcode', 'usb'
            $table->foreignId('logged_by')->constrained('users')->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'logged_at']);
            $table->index('logged_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gate_logs');
    }
};
