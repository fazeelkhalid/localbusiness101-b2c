<?php

namespace App\Http\Controllers;

use App\Enums\ErrorResponseEnum;
use App\Http\Controllers\Controller;
use App\Http\Mapper\PaymentMapper;
use App\Http\Requests\Payment\PaymentRequest;
use App\Http\Responses\Payment\PaymentResponse;
use App\Models\Payment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

    public function getPayment($payment_id){
        try {
            $payment = Payment::getAndSetPaymentIsSeen($payment_id);
            $payment = PaymentMapper::mapStoredpaymentRequestToResponse($payment);
            return new PaymentResponse("Payment found successfully", $payment, 200);
        } catch (ModelNotFoundException $e) {
            return ErrorResponseEnum::$PAYMENT_NOT_FOUND;
        }
    }

}
