<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number', 50)->unique();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('enrollment_id')->nullable()->constrained('enrollments')->onDelete('set null');
            $table->enum('type', ['matricula', 'mensalidade', 'material', 'uniforme', 'outro'])->default('mensalidade');
            $table->decimal('amount', 10, 2);
            $table->integer('month')->nullable(); // Mês da mensalidade (2-11)
            $table->integer('year')->default(date('Y'));
            $table->date('due_date');
            $table->date('payment_date')->nullable();
            $table->enum('status', ['pending', 'paid', 'overdue', 'cancelled'])->default('pending');
            $table->enum('payment_method', ['cash', 'mpesa', 'emola', 'bank', 'multicaixa'])->nullable();
            $table->string('transaction_id')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('penalty', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
