<?php

namespace App\Http\Middleware;

use App\Enums\Configuration;
use App\Enums\ConfigurationEnum;
use App\Enums\ErrorResponseEnum;
use App\Http\Services\AcquirerService;
use App\Http\Services\ConfigurationService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplicationIpAndPortMiddleware
{
    protected AcquirerService $acquirerService;
    protected ConfigurationService $configurationService;

    public function __construct(AcquirerService $acquirerService, ConfigurationService $configurationService)
    {
        $this->acquirerService = $acquirerService;
        $this->configurationService = $configurationService;
    }

    public function handle(Request $request, Closure $next)
    {
        $IP_PORT_RESTRICTION_ENABLED = ConfigurationEnum::$IP_PORT_RESTRICTION_ENABLED;

        if ($this->configurationService->getConfigurationValue($IP_PORT_RESTRICTION_ENABLED->key) == $IP_PORT_RESTRICTION_ENABLED->value) {
            $requestIp = $request->ip();
            $requestPort = $request->getPort();
            $application = $this->acquirerService->get('acquirer')->application;

            if (!($requestIp == $application->host_ip) || !($requestPort == $application->host_port)) {
                return ErrorResponseEnum::$UAA401;
            }
        }
        return $next($request);
    }
}
