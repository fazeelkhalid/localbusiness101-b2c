<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('business_profiles', function (Blueprint $table) {
            $table->string('slug');
            $table->string('card_image_url')->default('default_card_image_url.jpg');
        });
    }

    public function down()
    {
        Schema::table('business_profiles', function (Blueprint $table) {
            $table->dropColumn(['slug', 'card_image_url']);
        });
    }
};
