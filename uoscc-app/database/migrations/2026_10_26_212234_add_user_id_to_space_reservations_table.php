<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Link each reservation request to the supporter who made it, so the
     * requester can follow its status.
     */
    public function up(): void
    {
        Schema::table('space_reservations', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('space_id')->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('space_reservations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
