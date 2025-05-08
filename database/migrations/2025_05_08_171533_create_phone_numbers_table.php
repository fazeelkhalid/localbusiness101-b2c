<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('phone_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number')->unique();
            $table->string('dialing_regex')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('phone_numbers');
    }

};
