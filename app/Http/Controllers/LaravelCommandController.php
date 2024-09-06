<?php

namespace App\Http\Controllers;

use App\Http\Services\AcquirerService;
use App\Http\Services\LaravelCommandService;

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
}
