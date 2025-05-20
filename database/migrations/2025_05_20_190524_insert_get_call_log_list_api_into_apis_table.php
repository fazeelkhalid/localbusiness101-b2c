<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class InsertGetCallLogListApiIntoApisTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('apis')->insert([
            'api_code' => 'getCallLogList',
            'name' => 'Get Call Log List',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('apis')->where('api_code', 'getCallLogList')->delete();
    }
}
