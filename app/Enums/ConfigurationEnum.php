<?php

namespace App\Enums;


class ConfigurationEnum
{
    public static Configuration $IP_PORT_RESTRICTION_ENABLED;
    public static Configuration $ALLOW_CALL_RECORDING;
    public static Configuration $CALL_DELAY_LATENCY;
    public static Configuration $IS_ADMIN_STATS_UNABLE;

    public static function initialize()
    {
        self::$IP_PORT_RESTRICTION_ENABLED = new Configuration('ip_and_port_restrictions', "enabled");
        self::$ALLOW_CALL_RECORDING = new Configuration('ALLOW_CALL_RECORDING', "0");
        self::$IS_ADMIN_STATS_UNABLE = new Configuration('IS_ADMIN_STATS_UNABLE', "0");
        self::$CALL_DELAY_LATENCY = new Configuration('CALL_DELAY_LATENCY', "10");
    }

}
