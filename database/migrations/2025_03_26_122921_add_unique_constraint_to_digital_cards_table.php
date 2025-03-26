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
        Schema::table('digital_cards', function (Blueprint $table) {
            // Add unique constraint for owner_name, designation, and business_name
            $table->unique(['owner_name', 'designation', 'business_name'], 'unique_digital_card_constraint');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('digital_cards', function (Blueprint $table) {
            // Drop the unique constraint
            $table->dropUnique('unique_digital_card_constraint');
        });
    }
};
