<?php

namespace App\Http\Mapper;

class PaymentMapper
{
    public static function mapStoredpaymentRequestToResponse($storedData, $paymentURL = null)
    {
        $response = [
            "method" => $storedData["method"],
            "payment_id" => $storedData["payment_id"],
            "amount" => $storedData["amount"],
            "description" => $storedData["description"]
        ];

        if (!empty($paymentURL)) {
            $response["payment_url"] = $paymentURL;
        }

        return $response;
    }
}
