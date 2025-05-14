<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallLogsTable extends Migration
{
    public function up()
    {
        Schema::create('call_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('caller_number_id');
            $table->string('receiver_number');
            $table->integer('talk_time')->default(0);
            $table->string('twilio_sid');
            $table->string('twilio_recording_sid')->nullable();
            $table->string('recording_url')->nullable();

            // Explicitly set timestamps with default CURRENT_TIMESTAMP
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('caller_number_id')->references('id')->on('phone_numbers')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('call_logs');
    }
}
