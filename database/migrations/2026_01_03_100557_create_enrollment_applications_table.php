<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('enrollment_applications', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['NEW', 'RENEWAL']);
            $table->string('status')->default('PENDING'); // PENDING, IN_REVIEW, DOCUMENT_DELIVERED, APPROVED, REJECTED, ENROLLED
            $table->foreignId('student_id')->nullable()->constrained('students')->onDelete('cascade');
            $table->json('parent_data');
            $table->json('student_data');
            $table->integer('academic_year');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('payment_status')->default('AWAITING_PAYMENT'); // AWAITING_PAYMENT, PAID, REJECTED
            $table->string('payment_reference')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('payment_proof_path')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollment_applications');
    }
};
