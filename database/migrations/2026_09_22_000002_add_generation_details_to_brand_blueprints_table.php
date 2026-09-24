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
        Schema::table('brand_blueprints', function (Blueprint $table) {
            // Which generator produced this, so a fallback is visible rather than silent.
            $table->string('generator')->default('template')->after('payload');
            $table->string('model')->nullable()->after('generator');
            $table->unsignedInteger('generation_ms')->nullable()->after('model');
            $table->text('failure_reason')->nullable()->after('generation_ms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brand_blueprints', function (Blueprint $table) {
            $table->dropColumn(['generator', 'model', 'generation_ms', 'failure_reason']);
        });
    }
};
