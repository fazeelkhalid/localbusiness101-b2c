<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $allowedOrigins = [
            'https://admin.probusiness101.com',
        ];

        $origin = $request->header('Origin');

        // Check if origin is in allowed list
        if (in_array($origin, $allowedOrigins)) {
            // For preflight requests
            if ($request->isMethod('OPTIONS')) {
                $response = response('', 200);
                $response->headers->set('Access-Control-Allow-Origin', $origin);
                $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
                $response->headers->set('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Requested-With');
                $response->headers->set('Access-Control-Allow-Credentials', 'true');
                $response->headers->set('Access-Control-Max-Age', '86400');
                return $response;
            }

            // For actual requests
            $response = $next($request);
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Requested-With');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');

            return $response;
        }

        return $next($request);
    }
}
