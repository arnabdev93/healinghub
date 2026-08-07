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
        Schema::table('orders', function (Blueprint $table) {

            if (!Schema::hasColumn('orders','appointment_id')) {
                $table->unsignedBigInteger('appointment_id')->nullable()->after('address_id');
            }

            if (!Schema::hasColumn('orders','notes')) {
                $table->text('notes')->nullable()->after('appointment_id');
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            if (Schema::hasColumn('orders','appointment_id')) {
                $table->dropColumn('appointment_id');
            }

        });
    }
};
