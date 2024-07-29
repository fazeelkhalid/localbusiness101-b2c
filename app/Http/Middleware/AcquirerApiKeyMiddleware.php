<?php

namespace App\Http\Middleware;

use App\Enums\ErrorResponseEnum;
use Closure;
use Illuminate\Http\Request;
use App\Http\Services\AcquirerService;
class AcquirerApiKeyMiddleware
{
    protected AcquirerService $acquirerService;

    public function __construct(AcquirerService $acquirerService)
    {
        $this->acquirerService = $acquirerService;
    }

    public function handle(Request $request, Closure $next)
    {
        if (!$request->header('api-key')) {
            return ErrorResponseEnum::$AKM401;
        }

        $apiKey = $request->header('api-key');
        $acquirer = $this->acquirerService->fetchAcquirerDataByKey($apiKey);

        if(!$acquirer || !$acquirer->user){
            return ErrorResponseEnum::$ANF404;
        }

        $this->acquirerService->set('acquirer', $acquirer);

        return $next($request);

    }
}
