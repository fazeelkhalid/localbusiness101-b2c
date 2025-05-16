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
    public static ErrorResponse $PHONE_NUMBERS_NOT_ASSIGN_200;
    public static ErrorResponse $BUSINESS_PROFILE_ANALYTICS_NOT_FOUND;

    public static ErrorResponse $DIGITAL_CARD_NOT_FOUND;
    public static ErrorResponse $INVALID_OR_NOT_ASSIGN_NUMBER_404;
    public static ErrorResponse $TWILIO_RECORDING_SID_MISSING_422;
    public static ErrorResponse $ERROR_DUMPING_WEBHOOK_500;

    public static function initialize(): void
    {
        self::$UENE422 = new ErrorResponse('The email has already been taken.', 422);
        self::$RNE404 = new ErrorResponse('Route not found or incorrect method.', 404);
        self::$UAA401 = new ErrorResponse("Unauthorized access.", 401);
        self::$AKM401 = new ErrorResponse( 'API key missing', 401);
        self::$IAK401 = new ErrorResponse('Invalid API key', 401);
        self::$BPNF404 = new ErrorResponse('Business Profile not found', 404);
        self::$CFRF404 = new ErrorResponse('Contact request not found', 404);
        self::$ANF404 = new ErrorResponse('Acquirer not found, Or not assign to any user', 404);
        self::$INVALID_JWT_TOKEN = new ErrorResponse('Invalid JWT Token', 401);
        self::$AUTHORIZATION_HEADER_MISSING = new ErrorResponse('Authorization header missing', 401);
        self::$PAYMENT_NOT_FOUND = new ErrorResponse('Payment details not found', 404);
        self::$BUSINESS_PROFILE_ANALYTICS_NOT_FOUND = new ErrorResponse('business profile analytics not found', 404);
        self::$DIGITAL_CARD_NOT_FOUND = new ErrorResponse('Digital card not found', 404);
        self::$PHONE_NUMBERS_NOT_ASSIGN_200 = new ErrorResponse('No phone numbers assigned', 200);
        self::$INVALID_OR_NOT_ASSIGN_NUMBER_404 = new ErrorResponse('Number is invalid or not assigned to your account. Please select another number.', 404);
        self::$TWILIO_RECORDING_SID_MISSING_422 = new ErrorResponse('Twilio Recording Id missing.');
        self::$ERROR_DUMPING_WEBHOOK_500 = new ErrorResponse('Error while dumping webhook in the system', 500);

    }
}

