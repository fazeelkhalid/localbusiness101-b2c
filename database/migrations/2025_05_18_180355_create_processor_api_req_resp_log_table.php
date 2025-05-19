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
        // Drop the old table if it exists
        Schema::dropIfExists('processor_api_req_resp_log');

        // Create the new table
        Schema::create('processor_api_req_resp_logs', function (Blueprint $table) {
            $table->id();
            $table->string('method');
            $table->text('url');
            $table->json('request_headers')->nullable();
            $table->json('request_body')->nullable();
            $table->integer('http_status_code')->nullable();
            $table->json('response_body')->nullable();
            $table->text('exception')->nullable();
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('processor_api_req_resp_logs');
    }
};
