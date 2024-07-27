<?php

namespace App\Http\Middleware;

use App\Enums\ErrorResponseEnum;
use App\Http\Services\AcquirerService;
use App\Models\BusinessProfile;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FetchAcquirerBusinessProfileMiddleware
{

    protected AcquirerService $acquirerService;

    public function __construct(AcquirerService $acquirerService)
    {
        $this->acquirerService = $acquirerService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $acquirer = $this->acquirerService->get("acquirer");
        $userId = $acquirer->user->id;

        $businessProfile = BusinessProfile::where('user_id', $userId)->first();

        if(!$businessProfile){
            return ErrorResponseEnum::$BPNF404;
        }

        $this->acquirerService->set("businessProfile", $businessProfile);

        return $next($request);
    }
}
