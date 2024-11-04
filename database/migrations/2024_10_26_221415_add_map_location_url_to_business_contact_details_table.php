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
        Schema::table('business_contact_details', function (Blueprint $table) {
            $table->string('map_location_url')->nullable()->after('business_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_contact_details', function (Blueprint $table) {
            $table->dropColumn('map_location_url');
        });
    }
};
