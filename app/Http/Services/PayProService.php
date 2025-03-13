<?php

namespace App\Http\Services;

use App\Exceptions\ErrorException;
use Illuminate\Support\Facades\Http;

class PayProService
{

    private static function getAuthToken()
    {
        $data = [
            'clientid' => env('PAYPRO_CLIENT_ID'),
            'clientsecret' => env('PAYPRO_CLIENT_SECRET')
        ];
        $response = PayProRequestService::request('/auth', 'POST', $data);
        $token = $response->header('token');

        return $token;
    }


    public static function createOrder($orderNumber, $amount, $customerName, $customerEmail)
    {
        $token = self::getAuthToken();

        $issueDate = date('d/m/Y');
        $dueDate = date('d/m/Y', strtotime('+3 days'));
        $payload = [
            [
                'MerchantId' => env('PAYPRO_MERCHANT_ID')
            ],
            [
                'OrderNumber' => $orderNumber,
                'OrderDueDate' => $dueDate,
                'OrderType' => 'Service',
                'IssueDate' => $issueDate,
                'OrderExpireAfterSeconds' => '0',
                'CustomerName' => $customerName,
                'CustomerMobile' => '',
                'CustomerEmail' => '',
                'CustomerAddress' => '',
                'Description' => 'test',
                'CurrencyAmount' => (string)$amount,
                'Currency' => 'USD',
                'IsConverted' => 'true'
            ]
        ];

        $headers = [
            'token' => $token,
            'Content-Type' => 'application/json'
        ];

        $response = PayProRequestService::request('/co', 'POST', $payload, $headers);

        $responseData = $response->json();
        $iframeUrl = null;
        $PayProId = null;
        if (isset($responseData[0]['Status']) && $responseData[0]['Status'] === '00') {
            $iframeUrl = $responseData[1]['IframeClick2Pay'] ?? null;
            $PayProId = $responseData[1]['PayProId'] ?? null;
        } else {
            throw new ErrorException('Payment processor Response is not okay', $responseData, 500);
        }

        if(!$iframeUrl){
            throw new ErrorException('iframe not present in the payment processor response.', $responseData, 500);
        }

        if(!$PayProId){
            throw new ErrorException('paymentID not present in the payment processor response.', $responseData, 500);
        }

        return [$iframeUrl, $PayProId];
    }
}
