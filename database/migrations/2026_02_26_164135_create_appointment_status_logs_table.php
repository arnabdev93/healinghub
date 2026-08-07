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
        Schema::create('appointment_status_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appointment_id');
            $table->unsignedBigInteger('changed_by');
            $table->enum('changed_by_role', ['doctor', 'customer']);
            $table->string('old_status')->nullable();
            $table->string('new_status');
            $table->timestamp('changed_at');
            $table->timestamps();

            $table->foreign('appointment_id')
                ->references('id')
                ->on('book_appointments')
                ->onDelete('cascade');

            $table->foreign('changed_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_status_logs');
    }
};
