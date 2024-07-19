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
        Schema::create('api_req_resp_logs', function (Blueprint $table) {
            $table->id();
            $table->string('message_trace_uuid')->nullable();
            $table->text('request_header')->nullable();
            $table->text('payload')->nullable();
            $table->text('complete_url')->nullable();
            $table->string('http_endpoint')->nullable();
            $table->string('http_method')->nullable();
            $table->string('http_status_code')->nullable();
            $table->text('response_header')->nullable();
            $table->text('response_body')->nullable();
            $table->integer('response_time')->nullable();
            $table->string('source_ip')->nullable();
            $table->string('source_port')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_req_resp_logs');
    }
};
