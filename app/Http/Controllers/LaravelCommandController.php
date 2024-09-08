<?php

namespace App\Http\Controllers;

use App\Http\Services\AcquirerService;
use App\Http\Services\LaravelCommandService;
use App\Http\Utils\CustomUtils;
use Illuminate\Http\Request;

class LaravelCommandController extends Controller
{
    protected LaravelCommandService $commandService;
    protected AcquirerService $acquirerService;

    public function __construct(LaravelCommandService $commandService, AcquirerService $acquirerService)
    {
        $this->commandService = $commandService;
        $this->acquirerService = $acquirerService;
    }

    public function migrate()
    {
        $this->acquirerService->hasAuthorityOrThrowException("migrate");
        return $this->commandService->migrate();
    }

    public function rollback()
    {
        $this->acquirerService->hasAuthorityOrThrowException("rollback");
        return $this->commandService->rollback();
    }

    public function createStorageLink()
    {
        $this->acquirerService->hasAuthorityOrThrowException("createStorageLink");
        return $this->commandService->createStorageLink();
    }

    public function imageHost(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $image = $request->file('image');
        if ($image) {
            $filename = 'img.' . $image->getClientOriginalExtension();
            $fullImagePath = url('/') . CustomUtils::uploadProfileImage('/orlando', $image, $filename);
            return response()->json([
                'message' => 'Image uploaded successfully!',
                'image_url' => $fullImagePath,
            ], 200);
        }

        return response()->json(['message' => 'No image uploaded'], 400);
    }
}
