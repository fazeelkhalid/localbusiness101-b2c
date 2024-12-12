<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('business_profile_useful_link', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_profile_id');
            $table->string('links');
            $table->string('tags_title');
            $table->timestamps();

            $table->foreign('business_profile_id')->references('id')->on('business_profiles')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_profile_useful_link');
    }
};
