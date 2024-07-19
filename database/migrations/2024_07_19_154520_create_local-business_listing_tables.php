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
        Schema::create('business_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained("users")->onDelete('cascade');
            $table->string('business_profiles_key');
            $table->string('title');
            $table->text('description');
            $table->text('short_intro');
            $table->text('keywords');
            $table->string('og_image');
            $table->string('og_type');
            $table->string('tab_title');
            $table->string('font_style');
            $table->string('heading_color');
            $table->string('heading_size');
            $table->string('fav_icon');
            $table->timestamps();
        });

        Schema::create('business_contact_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_profile_id')->constrained('business_profiles')->onDelete('cascade');
            $table->string('business_phone');
            $table->string('business_email');
            $table->string('business_address');
            $table->timestamps();
        });

        Schema::create('client_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_profile_id')->constrained('business_profiles')->onDelete('cascade');
            $table->string('country')->nullable();
            $table->string('browser')->nullable();
            $table->string('device_type')->nullable();
            $table->string('referrer_url')->nullable();
            $table->ipAddress();
            $table->timestamps();
        });

        Schema::create('contact_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_profile_id')->constrained('business_profiles')->onDelete('cascade');
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('subject')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_requests');
        Schema::dropIfExists('client_logs');
        Schema::dropIfExists('business_contact_details');
        Schema::dropIfExists('business_profiles');
    }
};
