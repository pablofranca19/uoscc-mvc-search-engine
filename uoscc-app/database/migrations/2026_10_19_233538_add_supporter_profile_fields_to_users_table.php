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
        Schema::table('users', function (Blueprint $table) {
            $table->text('supporter_skills')->nullable()->after('password');
            $table->text('supporter_interests')->nullable()->after('supporter_skills');
            $table->string('supporter_availability', 500)->nullable()->after('supporter_interests');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['supporter_skills', 'supporter_interests', 'supporter_availability']);
        });
    }
};
