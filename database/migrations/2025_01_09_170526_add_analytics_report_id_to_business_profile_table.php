<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_profiles', function (Blueprint $table) {
            $table->unsignedBigInteger('analytics_report_id')->nullable()->after('heading_color');
            $table->longText('html_report')->nullable()->after('analytics_report_id');
            $table->foreign('analytics_report_id')
                ->references('id')
                ->on('business_profile_analytics_report')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_profiles', function (Blueprint $table) {
            $table->dropForeign(['analytics_report_id']);
            $table->dropColumn('analytics_report_id');
            $table->dropColumn('html_report');
        });
    }
};
