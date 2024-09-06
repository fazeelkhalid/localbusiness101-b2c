<?php

namespace App\Http\Controllers;

use App\Http\Services\AcquirerService;
use App\Http\Services\ClientLogsService;
use Illuminate\Http\Request;

class ClientLogsController extends Controller
{
    protected AcquirerService $acquirerService;
    protected ClientLogsService $clientLogsService;

    public function __construct(AcquirerService $acquirerService, ClientLogsService $clientLogsService)
    {
        $this->acquirerService = $acquirerService;
        $this->clientLogsService = $clientLogsService;
    }

    public function clientLogs(Request $request)
    {
        $this->acquirerService->hasAuthorityOrThrowException("clientLogs");
        return $this->clientLogsService->clientLogs($request);
    }

    public function fetchBusinessProfileStats()
    {
        $this->acquirerService->hasAuthorityOrThrowException("fetchBusinessProfileStats");
        return $this->clientLogsService->fetchBusinessProfileStats();
    }
}
