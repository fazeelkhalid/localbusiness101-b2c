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
        Schema::create('webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->json('request_headers');
            $table->json('payload');
            $table->timestamp('received_at')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->text('url')->nullable();
            $table->enum('status', ['Pending', 'InProgress', 'Processed', 'Failed'])->default('Pending');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_logs');
    }
};
