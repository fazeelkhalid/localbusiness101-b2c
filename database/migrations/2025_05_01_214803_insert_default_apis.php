<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::table('apis')->insert([
            ['api_code' => 'updateUserBusinessProfile', 'name' => 'update user business profile', 'created_at' => now(), 'updated_at' => now()],
            ['api_code' => 'createUser', 'name' => 'Create New user', 'created_at' => now(), 'updated_at' => now()],
            ['api_code' => 'getUserList', 'name' => 'Get all the User list', 'created_at' => now(), 'updated_at' => now()],
            ['api_code' => 'createDigitalCard', 'name' => 'Create Digital Card', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        DB::table('apis')->whereIn('api_code', [
            'updateUserBusinessProfile',
            'createUser',
            'getUserList',
            'createDigitalCard'
        ])->delete();
    }
};
