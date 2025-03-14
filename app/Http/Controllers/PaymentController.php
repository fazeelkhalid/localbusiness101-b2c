<?php

namespace App\Http\Controllers;

use App\Enums\ErrorResponseEnum;
use App\Http\Mapper\PaymentMapper;
use App\Http\Requests\Payment\PaymentRequest;
use App\Http\Requests\Payment\PaymentStatusUpdateRequest;
use App\Http\Responses\Payment\PaymentResponse;
use App\Http\Services\Client\HttpNotificationService;
use App\Http\Services\PayProService;
use App\Models\Payment;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PaymentController extends Controller
{
    public function createPayment(PaymentRequest $request)
    {
        $validatedData = $request->validated();
        $payment = Payment::createPayment($validatedData);

        if ($validatedData["method"] === "paypro") {
            $payProPayment = PayProService::createOrder($payment["payment_id"], $payment["amount"], $payment['client_name'], $payment['client_email']);
            Payment::UpdatePayProPaymentInfo($payProPayment[1], $payProPayment[0], $payment);
            $payment = PaymentMapper::mapStoredpaymentRequestToResponse($payment, $payProPayment[0]);
        } else {
            $payment = PaymentMapper::mapStoredpaymentRequestToResponse($payment);
        }
        return new PaymentResponse("Payment created", $payment, 201);
    }

    public function getPayment($payment_id)
    {
        try {
            $payment = Payment::getAndSetPaymentIsSeen($payment_id);
            $payment = PaymentMapper::mapStoredpaymentRequestToResponse($payment, $payment->payment_link);
            return new PaymentResponse("Payment found successfully", $payment, 200);
        } catch (ModelNotFoundException $e) {
            return ErrorResponseEnum::$PAYMENT_NOT_FOUND;
        }
    }

    public function updatePaymentStatus($payment_id, PaymentStatusUpdateRequest $request)
    {

        $validatedData = $request->validated();
        $payment = Payment::where('payment_id', $payment_id)->first();
        if (!$payment) {
            return ErrorResponseEnum::$PAYMENT_NOT_FOUND;
        }

        $message = '';
        if ($validatedData['status'] === 'success') {
            $payment->is_paid = true;
            $message = "Payment marked as successful!!! You will received a notification shortly";
            HttpNotificationService::sendInvoiceEmail($payment, $payment->client_email);
            HttpNotificationService::sendInvoiceEmail($payment, 'info@fastdevlabs.com');
        } else {
            $payment->is_paid = false;
            $message = "Payment marked as failed!!! please contact our customer support";
        }
        $payment->stripe_response = $validatedData['stripe_response'];
        $payment->save();
        $payment = PaymentMapper::mapStoredpaymentRequestToResponse($payment);
        return new PaymentResponse($message, $payment, 200);
    }
}
