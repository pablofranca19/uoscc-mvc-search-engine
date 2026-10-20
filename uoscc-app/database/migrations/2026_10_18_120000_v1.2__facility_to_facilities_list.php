<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Replace the single `facility` string with a `facilities` JSON list so a
     * space can expose several amenities (the UI renders them as a list).
     */
    public function up(): void
    {
        Schema::table('spaces', function (Blueprint $table) {
            $table->json('facilities')->nullable()->after('capacity');
        });

        Schema::table('spaces', function (Blueprint $table) {
            $table->dropColumn('facility');
        });
    }

    /**
     * Reverse the migration, restoring the single `facility` string column.
     */
    public function down(): void
    {
        Schema::table('spaces', function (Blueprint $table) {
            $table->string('facility')->nullable()->after('capacity');
        });

        Schema::table('spaces', function (Blueprint $table) {
            $table->dropColumn('facilities');
        });
    }
};
