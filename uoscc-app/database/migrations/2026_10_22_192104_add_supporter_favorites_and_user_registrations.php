<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Persist the supporter dashboard data that used to live in the session:
     * favorites (organizations and activities) and the supporter's own
     * registrations, including the confirmed participation that unlocks the
     * certificate.
     */
    public function up(): void
    {
        Schema::create('supporter_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('favoritable');
            $table->timestamps();

            $table->unique(['user_id', 'favoritable_type', 'favoritable_id'], 'supporter_favorites_unique');
        });

        Schema::table('registrations', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('activity_id')->constrained()->nullOnDelete();
            $table->timestamp('completed_at')->nullable()->after('registered_at');

            $table->unique(['activity_id', 'user_id'], 'registrations_activity_user_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * MySQL/MariaDB let the composite unique index serve the `activity_id`
     * foreign key, so a replacement index is created before dropping it.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->index('activity_id', 'registrations_activity_id_index');
        });

        Schema::table('registrations', function (Blueprint $table) {
            $table->dropUnique('registrations_activity_user_unique');
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn('completed_at');
        });

        Schema::dropIfExists('supporter_favorites');
    }
};
