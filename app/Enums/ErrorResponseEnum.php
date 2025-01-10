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

    public static ErrorResponse $INVALID_JWT_TOKEN;

    public static ErrorResponse $AUTHORIZATION_HEADER_MISSING;

    public static ErrorResponse $PAYMENT_NOT_FOUND;


    public static ErrorResponse $BUSINESS_PROFILE_ANALYTICS_NOT_FOUND;

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
        self::$INVALID_JWT_TOKEN = new ErrorResponse('Invalid JWT Token', 401);
        self::$AUTHORIZATION_HEADER_MISSING = new ErrorResponse('Authorization header missing', 401);
        self::$PAYMENT_NOT_FOUND = new ErrorResponse('Payment details not found', 404);
        self::$BUSINESS_PROFILE_ANALYTICS_NOT_FOUND = new ErrorResponse('business profile analytics not found', 404);

    }
}

