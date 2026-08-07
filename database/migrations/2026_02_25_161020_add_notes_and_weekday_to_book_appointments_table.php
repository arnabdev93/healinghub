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
        Schema::table('book_appointments', function (Blueprint $table) {
            $table->string('weekday')->after('booking_date');
            $table->text('notes')->nullable()->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('book_appointments', function (Blueprint $table) {
            $table->dropColumn(['weekday', 'notes']);
        });
    }
};
