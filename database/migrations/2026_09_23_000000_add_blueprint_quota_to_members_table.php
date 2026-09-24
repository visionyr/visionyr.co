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
        Schema::table('members', function (Blueprint $table) {
            $table->unsignedInteger('blueprint_quota_used')->default(0)->after('is_active');

            // The month the counter belongs to, as "YYYY-MM". When it no longer
            // matches the current month the counter has rolled over, so no
            // scheduled job is needed to reset anyone.
            $table->string('blueprint_quota_period', 7)->nullable()->after('blueprint_quota_used');

            $table->timestamp('blueprint_quota_refreshed_at')->nullable()->after('blueprint_quota_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn([
                'blueprint_quota_used',
                'blueprint_quota_period',
                'blueprint_quota_refreshed_at',
            ]);
        });
    }
};
