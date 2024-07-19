<?php

namespace App\Http\Services;

use App\Enums\EndpointActionEnum;
use App\Exceptions\ErrorException;
use \Illuminate\Database\Eloquent\Model;
use App\Models\Acquirer;
use mysql_xdevapi\Exception;


class AcquirerService
{
    protected array $data = [];

    public function set($key, $value): void
    {
        $this->data[$key] = $value;
    }

    public function get($key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    public function fetchAcquirerDataByKey($key): Model | null
    {
        return Acquirer::with(['application', 'allowedAPIs', 'configurations'])
            ->where('key', $key)
            ->first();
    }

    /**
     * @throws ErrorException
     */
    public function hasAuthorityOrThrowException($apiCode):void
    {
        $acquirer = $this->get("acquirer");

        $isAllowed = $acquirer->allowedAPIs->contains(function ($allowedAPI) use ($apiCode) {
            return $allowedAPI->api_code === $apiCode;
        });

        if (!$isAllowed) {
            throw new ErrorException("Acquirer not defined or associated with operation ID " . $apiCode, 403);
        }
    }
}
