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
        DB::table('apis')->insert([
            ['api_code' => 'migrate', 'name' => 'Migrate Database', 'created_at' => now(), 'updated_at' => now()],
            ['api_code' => 'createStorageLink', 'name' => 'Create Storage Link', 'created_at' => now(), 'updated_at' => now()],
            ['api_code' => 'rollback', 'name' => 'Migrate Rollback', 'created_at' => now(), 'updated_at' => now()],
            ['api_code' => 'createReview', 'name' => 'Create Review', 'created_at' => now(), 'updated_at' => now()],
            ['api_code' => 'getProfileReviewAndRatingList', 'name' => 'Get Profile Review and Rating List', 'created_at' => now(), 'updated_at' => now()],
            ['api_code' => 'clientLogs', 'name' => 'Dump Client Logs', 'created_at' => now(), 'updated_at' => now()],
            ['api_code' => 'createContactFormRequest', 'name' => 'Create Contact Form Request', 'created_at' => now(), 'updated_at' => now()],
            ['api_code' => 'fetchBusinessProfileStats', 'name' => 'Fetch Business Profile Stats', 'created_at' => now(), 'updated_at' => now()],
            ['api_code' => 'getContactFormRequest', 'name' => 'Get Contact Form Request', 'created_at' => now(), 'updated_at' => now()],
            ['api_code' => 'getContactFormRequestList', 'name' => 'Get Contact Form Request List', 'created_at' => now(), 'updated_at' => now()],
            ['api_code' => 'deleteContactFormRequest', 'name' => 'Delete Contact Form Request', 'created_at' => now(), 'updated_at' => now()],
            ['api_code' => 'createUserBusinessProfile', 'name' => 'Create User Business profile', 'created_at' => now(), 'updated_at' => now()],
            ['api_code' => 'createCategory', 'name' => 'Create Category', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('apis')->whereIn('api_code', [
            'migrate', 'createStorageLink', 'rollback', 'createReview', 'getProfileReviewAndRatingList',
            'clientLogs', 'createContactFormRequest', 'fetchBusinessProfileStats', 'getContactFormRequest',
            'getContactFormRequestList', 'deleteContactFormRequest', 'createUserBusinessProfile', 'createCategory',
        ])->delete();
    }
};
