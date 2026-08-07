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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned()->index();
            $table->string('image');
            $table->mediumText('about')->nullable();
            $table->string('specialist')->nullable();
            $table->double('consult_fee_phone')->nullable();
            $table->double('consult_fee_vdo')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_acc_no')->nullable();
            $table->string('bank_ifsc_code')->nullable();
            $table->string('upi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
