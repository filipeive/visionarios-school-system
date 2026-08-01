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
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->string('license_key')->unique();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->integer('grace_period_days')->default(7);
            $table->enum('status', ['active', 'grace_period', 'suspended'])->default('active');
            $table->string('plan')->default('standard');
            $table->timestamp('last_ping_at')->nullable();
            $table->text('signature')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
