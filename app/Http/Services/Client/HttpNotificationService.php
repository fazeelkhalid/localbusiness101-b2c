<?php

namespace App\Http\Services\Client;


use App\Enums\ConfigurationEnum;
use App\Http\Services\ConfigurationService;
use App\Http\Utils\CustomUtils;
use App\Models\ApplicationConfiguration;
use Illuminate\Support\Facades\Http;
use Ramsey\Uuid\Uuid;

class HttpNotificationService
{
    private static function sendEmail($requestBody, $apiKey)
    {
        return Http::withHeaders([
            'api-key' => $apiKey,
            'Content-Type' => 'application/json',
        ])->post(env('NOTIFICATION_SERVICE_URL') . '/api/notification/email', $requestBody);
    }


    public static function sendInvoiceEmail($payment, $receiverEmail)
    {
        $emailBody = CustomUtils::getInvoiceEmailBody($payment);
        $emailTitle = ApplicationConfiguration::getApplicationConfiguration("invoice_email_subject");
        $emailTitle = str_replace('%INVOICE_NO%', $payment->payment_id, $emailTitle);

        $requestBody = [
            'receiver_email' => $receiverEmail,
            'email_title' => $emailTitle,
            'email_body' => $emailBody,
            'message_trace_uuid' => Uuid::uuid4()->toString(),
        ];
        $email = HttpNotificationService::sendEmail($requestBody, ApplicationConfiguration::getApplicationConfiguration("NOTIFICATION_SERVICE_INVOICE_API_KEY"));
    }
}
