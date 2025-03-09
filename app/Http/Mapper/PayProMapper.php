<?php

namespace App\Http\Mapper;

use App\Http\Utils\CustomUtils;
use Exception;

class PayProMapper
{
    public static function mapErrorResponseT0ErrorExceptionBody(string $url, mixed $method, $exception = null): array
    {
        $response = [
            'request' => [
                'url' => $url,
                'method' => $method,
            ]
        ];

        if ($exception !== null) {
            $response['exception'] = $exception->getMessage();
        }

        return $response;
    }
}
