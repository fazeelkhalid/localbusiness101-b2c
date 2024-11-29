<?php

namespace App\Http\Mapper;

class PaymentMapper
{
    public static function mapStoredpaymentRequestToResponse($storedData)
    {
        return[
            "payment_id" => $storedData["payment_id"],
            "amount" => $storedData["amount"],
            "description" => $storedData["description"]
        ];
    }

}
