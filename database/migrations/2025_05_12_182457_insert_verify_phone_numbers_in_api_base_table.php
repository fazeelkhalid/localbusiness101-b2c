<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::table('apis')->insert([
            'api_code' => 'verifyPhoneNumbers',
            'name' => 'Verify Phone Numbers',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('apis')->where('api_code', 'getPhoneNumbers')->delete();
    }
};
