<?php

namespace App\Enums;


use App\Http\Responses\Error\ErrorResponse;

class ErrorResponseEnum
{
    public static ErrorResponse $UENE422;

    public static ErrorResponse $RNE404;

    public static ErrorResponse $UAA401;

    public static ErrorResponse $AKM401;

    public static ErrorResponse $IAK401;

    public static ErrorResponse $BPNF404;

    public static ErrorResponse $CFRF404;

    public static ErrorResponse $ANF404;


    public static function initialize(): void
    {
        self::$UENE422 = new ErrorResponse(['email' => 'The email has already been taken.'], 422);
        self::$RNE404 = new ErrorResponse(['error' => 'Route not found or incorrect method.'], 404);
        self::$UAA401 = new ErrorResponse("Unauthorized access.", 401);
        self::$AKM401 = new ErrorResponse(['error' => 'API key missing'], 401);
        self::$IAK401 = new ErrorResponse(['error' => 'Invalid API key'], 401);
        self::$BPNF404 = new ErrorResponse('Business Profile not found', 404);
        self::$CFRF404 = new ErrorResponse('Contact request not found', 404);
        self::$ANF404 = new ErrorResponse('Acquirer not found, Or not assign to any user', 404);
    }
}

