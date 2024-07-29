<?php

namespace App\Http\Controllers;

use App\Http\Services\ClientLogsService;
use Illuminate\Http\Request;

class ClientLogsController extends Controller
{
    protected ClientLogsService $clientLogsService;

    public function __construct(ClientLogsService $clientLogsService)
    {
        $this->clientLogsService = $clientLogsService;
    }

    public function clientLogs(Request $request)
    {
        return $this->clientLogsService->clientLogs($request);
    }

    public function fetchBusinessProfileStats()
    {
        return $this->clientLogsService->fetchBusinessProfileStats();
    }
}
