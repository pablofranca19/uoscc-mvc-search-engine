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
            $table->string("name");
            $table->string("description");
            $table->string("contact_email");
            $table->string("contact_phone");
            $table->string("social_media_links");
            $table->enum("focus_area", ["EducationAndCitizenship",
                "EnvironmentAndSustainability",
                "HealthcareAndCommunityWellbeing",
                "SocialAssistanceAndFamilySupport",
                "HumanRightsAndSocialInclusion",
                "CultureSportsAndLeisure"]);
        });
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("email");
            $table->foreignId("organization_id")->nullable()->constrained();
            $table->enum("role", ["Visitor", "Supporter"]);
        });
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string("cep");
            $table->string("neighbourhood");
            $table->string("street");
            $table->integer("number");
        });
        Schema::create('spaces', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("description");
            $table->integer("capacity");
            $table->string("facility");
            $table->foreignId("locations_id")->constrained();
        });
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->string("description");
            $table->date("activity_date");
            $table->foreignId("space_id")->constrained();
            $table->enum("activity_type", ["Event", "Project"]);
            $table->integer("capacity");
            $table->date("registration_deadline");
        });
        Schema::create('activity_organization', function (Blueprint $table) {
            $table->primary(["activity_id", "organization_id"]);
            $table->foreignId("activity_id")->constrained();
            $table->foreignId("organization_id")->constrained();
        });
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("contact");
            $table->timestamp("registered_at");
        });
        Schema::create('space_reservation', function (Blueprint $table) {
            $table->id();
            $table->string("request_name");
            $table->string("request_contact");
            $table->date("request_date");
            $table->enum("reservation_status", ["Pending", "Approved", "Rejected"])->default("Pending");
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
