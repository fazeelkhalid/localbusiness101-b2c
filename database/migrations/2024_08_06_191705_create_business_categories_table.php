<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('business_categories', function (Blueprint $table) {
            $table->id();
            $table->string('category_name');
            $table->foreignId('parent_category_id')
            ->nullable()
            ->constrained('business_categories')
            ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('business_categories');
    }
};
