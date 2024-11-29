<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Mapper\PaymentMapper;
use App\Http\Requests\Payment\PaymentRequest;
use App\Http\Responses\Payment\PaymentResponse;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function createPayment(PaymentRequest $request)
    {
        $validatedData = $request->validated();
        $payment = Payment::createPayment($validatedData);
        $payment = PaymentMapper::mapStoredpaymentRequestToResponse($payment);
        return new PaymentResponse("Payment created", $payment, 201);
    }

    

}
