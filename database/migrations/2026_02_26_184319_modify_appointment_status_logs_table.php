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
        Schema::table('appointment_status_logs', function (Blueprint $table) {
            $table->text('note')->nullable()->after('new_status');
            $table->dropColumn(['changed_by_role', 'old_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointment_status_logs', function (Blueprint $table) {
            $table->enum('changed_by_role', ['doctor', 'customer'])->after('changed_by');
            $table->string('old_status')->nullable()->after('changed_by_role');
            $table->dropColumn('note');
        });
    }
};
