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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appointment_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('doctor_id');

            $table->unsignedBigInteger('payment_method_id');

            $table->string('transaction_id')->nullable();

            $table->decimal('amount',10,2);

            $table->enum('status',['pending','paid','failed','refunded'])
                ->default('pending');

            $table->timestamp('paid_at')->nullable();

            $table->timestamps();

            $table->foreign('appointment_id')
                ->references('id')
                ->on('book_appointments')
                ->onDelete('cascade');

            $table->foreign('payment_method_id')
                ->references('id')
                ->on('payment_methods')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
