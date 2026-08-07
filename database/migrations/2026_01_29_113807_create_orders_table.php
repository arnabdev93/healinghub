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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('orderno',30);
            $table->integer('user_id')->unsigned()->index();
            $table->integer('address_id')->unsigned()->index();
            $table->enum('status',['pending','accept','reject','cancel'])->default('pending');
            $table->enum('type',['cart','prescription'])->default('cart');
            $table->double('subtotal')->nullable();
            $table->double('total')->nullable();
            $table->double('delivery_charge')->nullable();
            $table->double('packaging_charge')->nullable();
            $table->double('discount_amount')->nullable();
            $table->string('order_type',10)->nullable();
            $table->mediumText('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
