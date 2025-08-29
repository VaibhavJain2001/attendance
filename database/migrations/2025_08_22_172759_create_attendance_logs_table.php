<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('work_date'); // Day in org timezone
            $table->timestamp('clock_in_at')->nullable();
            $table->string('clock_in_photo')->nullable(); // path in storage
            $table->timestamp('clock_out_at')->nullable();
            $table->string('clock_out_photo')->nullable(); // path in storage
            $table->string('ip_address')->nullable();
            $table->string('device_info')->nullable();
            $table->string('location')->nullable(); // lat,long or address
            $table->timestamps();
            $table->unique(['user_id', 'work_date']); // one log per day
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_logs');
    }
};
