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
        Schema::create('business_profile_analytics_report', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_profile_id');
            $table->foreign('business_profile_id')->references('id')->on('business_profiles')->onDelete('cascade');
            $table->integer('days');
            $table->integer('total_click');
            $table->integer('total_impressions');
            $table->float('average_ctr');
            $table->float('average_bounce_rate');
            $table->float('average_time_on_page');
            $table->string('top_keyword');
            $table->string('top_area');
            $table->text('urls');
            $table->text('areas');
            $table->text('top_keywords');

            $table->text('click_by_area_graph_url');
            $table->text('search_keyword_counts_graph_url');
            $table->text('ctr_graph_url');
            $table->text('average_google_search_ranking_graph_url');
            $table->text('website_visitors_by_url_graph_url');

            $table->timestamp('record_generated');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_profile_analytics_report');
    }
};
