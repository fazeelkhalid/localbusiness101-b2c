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
        Schema::create('webhook_process_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('webhook_log_id')->index();
            $table->foreign('webhook_log_id')->references('id')->on('webhook_logs')->onDelete('cascade');

            $table->string('destination_url');
            $table->json('payload_sent')->nullable();
            $table->json('response_headers')->nullable();
            $table->longText('response_body')->nullable();
            $table->integer('http_status_code')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('executed_at')->nullable();
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_process_logs');
    }
};
