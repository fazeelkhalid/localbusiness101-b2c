<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('business_profiles', function (Blueprint $table) {
            $table->string('google_ads_tracking_code')->nullable()->after('about_cta_button_text');
        });
    }

    public function down(): void
    {
        Schema::table('business_profiles', function (Blueprint $table) {
            $table->dropColumn('google_ads_tracking_code');
        });
    }
};
