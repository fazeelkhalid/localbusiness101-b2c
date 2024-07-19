<?php

namespace App\Enums;

class Configuration
{
    public string $key;
    public string $value;

    public function __construct(string $key, string $value)
    {
        $this->key = $key;
        $this->value = $value;
    }
}
