<?php
// database/migrations/2024_01_01_000015_create_staff_leave_requests_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('staff_leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('teachers')->onDelete('cascade');
            $table->enum('leave_type', ['sick', 'vacation', 'personal', 'maternity', 'other'])->default('vacation');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('staff_leave_requests');
    }
};