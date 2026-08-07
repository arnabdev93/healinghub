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
            $table->string('meeting_provider')->nullable()->after('status');
            $table->text('meeting_link')->nullable()->after('meeting_provider');
            $table->string('meeting_code')->nullable()->after('meeting_link');
            $table->string('meeting_owner_email')->nullable()->after('meeting_code');
            $table->string('meeting_status')->nullable()->after('meeting_owner_email');
            $table->json('meeting_payload_json')->nullable()->after('meeting_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('book_appointments', function (Blueprint $table) {
            $table->dropColumn([
                'meeting_provider',
                'meeting_link',
                'meeting_code',
                'meeting_owner_email',
                'meeting_status',
                'meeting_payload_json',
            ]);
        });
    }
};
