<?php

namespace App\Http\Services;

use App\Enums\EndpointActionEnum;


class UserCredService
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
}
