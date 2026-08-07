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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned()->index();
            $table->string('name',50);
            $table->string('address');
            $table->string('country',30)->default('India');
            $table->string('state',50);
            $table->string('city',90);
            $table->string('pincode',6);
            $table->string('building')->nullable();
            $table->tinyInteger('is_delivery')->default(0);
            $table->string('receipent_name');
            $table->string('phone',15);
            $table->decimal('latitude',10,8);
            $table->decimal('longitude',10,8);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
