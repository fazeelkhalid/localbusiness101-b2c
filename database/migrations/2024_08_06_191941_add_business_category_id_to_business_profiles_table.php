<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('business_profiles', function (Blueprint $table) {
            $table->foreignId('business_category_id')
                ->nullable()
                ->constrained('business_categories')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('business_profiles', function (Blueprint $table) {
            $table->dropForeign(['business_category_id']);
            $table->dropColumn('business_category_id');
        });
    }
};
