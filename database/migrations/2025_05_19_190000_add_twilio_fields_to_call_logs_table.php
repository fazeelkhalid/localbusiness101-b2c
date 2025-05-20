<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTwilioFieldsToCallLogsTable extends Migration
{
    public function up()
    {
        Schema::table('call_logs', function (Blueprint $table) {
            $table->string('call_status')->nullable()->after('recording_url');
            $table->timestamp('call_start_time')->nullable()->after('call_status');
            $table->timestamp('call_end_time')->nullable()->after('call_start_time');
            $table->string('call_direction')->nullable()->after('call_end_time');
        });
    }

    public function down()
    {
        Schema::table('call_logs', function (Blueprint $table) {
            $table->dropColumn([
                'call_status',
                'call_start_time',
                'call_end_time',
                'call_direction'
            ]);
        });
    }
}
