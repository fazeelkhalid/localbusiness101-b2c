<?php

namespace App\Http\Services;

use App\Enums\EndpointActionEnum;
use Illuminate\Support\Facades\Artisan;

class LaravelCommandService
{
    public function migrate()
    {
        try {
            Artisan::call('migrate');
            return response()->json(['message' => 'Migrations ran successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function rollback()
    {
        try {
            Artisan::call('migrate:rollback');
            return response()->json(['message' => 'Migrations rolled back successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createStorageLink()
    {
        Artisan::call('storage:link');
        return response()->json(['message' => 'Storage link created successfully!']);
    }
}
