<?php

namespace App\Enums;


class ConfigurationEnum
{
    public static Configuration $IP_PORT_RESTRICTION_ENABLED;

    public static function initialize(): void
    {
        self::$IP_PORT_RESTRICTION_ENABLED = new Configuration('ip_and_port_restrictions', "enabled");
    }

}
