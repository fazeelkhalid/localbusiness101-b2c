<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('business_profiles', function (Blueprint $table) {
            $table->string('about_cta_button_text')->default('Free Estimates')->after('html_report');
        });
    }

    public function down()
    {
        Schema::table('business_profiles', function (Blueprint $table) {
            $table->dropColumn('about_cta_button_text');
        });
    }
};
