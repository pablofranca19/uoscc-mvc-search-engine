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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('social_media_links')->nullable();
            $table->enum('focus_area', [
                'EducationAndCitizenship',
                'EnvironmentAndSustainability',
                'HealthcareAndCommunityWellbeing',
                'SocialAssistanceAndFamilySupport',
                'HumanRightsAndSocialInclusion',
                'CultureSportsAndLeisure',
            ]);
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('role', ['Visitor', 'Supporter', 'OrganizationManager'])->default('Visitor');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('cep');
            $table->string('neighbourhood');
            $table->string('street');
            $table->integer('number');
            $table->timestamps();
        });

        Schema::create('spaces', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->integer('capacity');
            $table->string('facility');
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->date('activity_date');
            $table->foreignId('space_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->enum('activity_type', ['Event', 'Project']);
            $table->integer('capacity')->nullable();
            $table->date('registration_deadline')->nullable();
            $table->timestamps();
        });

        Schema::create('activity_organization', function (Blueprint $table) {
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->primary(['activity_id', 'organization_id']);
        });

        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
            $table->string('participant_name');
            $table->string('participant_contact');
            $table->timestamp('registered_at');
            $table->timestamps();
        });

        Schema::create('space_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('space_id')->constrained()->cascadeOnDelete();
            $table->string('requester_name');
            $table->string('requester_contact');
            $table->date('requested_date');
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('space_reservations');
        Schema::dropIfExists('registrations');
        Schema::dropIfExists('activity_organization');
        Schema::dropIfExists('activities');
        Schema::dropIfExists('spaces');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('users');
        Schema::dropIfExists('organizations');
    }
};
