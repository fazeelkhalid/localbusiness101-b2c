<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::table('apis')->insert([
            'api_code' => 'getCallLogRecording',
            'name' => 'API to stream and return call log recording MP3 by call SID.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('apis')->where('api_code', 'getCallLogRecording')->delete();
    }
};
