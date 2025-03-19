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
        // Create digital_cards table
        Schema::create('digital_cards', function (Blueprint $table) {
            $table->id();
            $table->string('header_image_url');
            $table->string('profile_image_url');
            $table->string('owner_name');
            $table->string('designation');
            $table->string('website_link')->nullable();
            $table->string('email')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('phone_number')->nullable();
            $table->text('gmb_links')->nullable();
            $table->text('about_business');
            $table->text('office_address')->nullable();
            $table->string('primary_color');
            $table->string('secondary_color');
            $table->string('slug')->unique();
            $table->string('business_name');
            $table->timestamps();
        });

        // Create office_hours table
        Schema::create('office_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('digital_card_id')->constrained('digital_cards')->onDelete('cascade');
            $table->enum('day_of_week', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->boolean('is_off')->default(false);

            // Create a unique constraint to ensure one entry per day per card
            $table->unique(['digital_card_id', 'day_of_week']);
        });

        // Create payment_methods table
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('digital_card_id')->constrained('digital_cards')->onDelete('cascade');
            $table->string('method_name');
            $table->text('description')->nullable();
            $table->string('payment_identifier')->nullable();
            $table->string('qr_code_image_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('office_hours');
        Schema::dropIfExists('digital_cards');
    }
};
