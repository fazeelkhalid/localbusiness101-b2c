<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('configurations', function (Blueprint $table) {
            $table->id();
            $table->string('config_code')->unique();
            $table->string('value');
            $table->timestamps();
        });

        Schema::create('apis', function (Blueprint $table) {
            $table->id();
            $table->string('api_code')->unique();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('acquirers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key');
            $table->timestamps();
        });

        Schema::create('acquirer_allowed_api', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acquirer_id')->constrained('acquirers')->onDelete('cascade');
            $table->foreignId('api_id')->constrained('apis')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acquirer_id')->constrained('acquirers')->onDelete('cascade');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('acquirer_allowed_api');
        Schema::dropIfExists('acquirers');
        Schema::dropIfExists('applications');
        Schema::dropIfExists('configurations');
        Schema::dropIfExists('apis');
    }
};

