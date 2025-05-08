<?php

namespace App\Http\Middleware;

use App\Enums\ErrorResponseEnum;
use App\Http\Services\AcquirerService;
use App\Models\BusinessProfile;
use Closure;
use Illuminate\Http\Request;

class FetchAcquirerBusinessProfileMiddleware
{
    protected AcquirerService $acquirerService;
    protected array $excludedRoutes;
    public function __construct(AcquirerService $acquirerService)
    {
        $this->acquirerService = $acquirerService;
        $this->excludedRoutes = [
            'GET' => [
                'api/phone',
            ],
            'POST' => [
            ],
        ];
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->shouldSkip($request)) {
            return $next($request);
        }

        $acquirer = $this->acquirerService->get("acquirer");
        $userId = $acquirer->user->id;

        $businessProfile = BusinessProfile::where('user_id', $userId)->first();

        if (!$businessProfile) {
            return ErrorResponseEnum::$BPNF404;
        }

        $this->acquirerService->set("businessProfile", $businessProfile);

        return $next($request);
    }

    private function shouldSkip(Request $request): bool
    {
        $method = $request->getMethod();
        $path = $request->path();

        return isset($this->excludedRoutes[$method]) &&
            in_array($path, $this->excludedRoutes[$method]);
    }
}
