<?php

namespace App\Http\Services\Client;

use App\Exceptions\ErrorException;
use App\Http\Services\Client\RequestLogger\HttpRequestLogger;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use PHPUnit\Exception;
use Throwable;

class TwilioHTTPHandler
{
    protected string $baseUrl;
    public string $accountSid;
    protected string $authToken;

    public function __construct()
    {
        $this->baseUrl = env('TWILIO_BASE_URL');
        $this->accountSid = env('TWILIO_ACCOUNT_SID');
        $this->authToken = env('TWILIO_AUTH_TOKEN');
    }

    /**
     * @throws ConnectionException
     * @throws ErrorException
     */
    public function sendRequest(string $url, string $method = 'GET', array $body = [], array $externalHeaders = [], bool $sendAsJson = false)
    {
        $defaultHeaders = [
            'Authorization' => 'Basic ' . base64_encode("{$this->accountSid}:{$this->authToken}")
        ];

        $headers = array_merge($defaultHeaders, $externalHeaders);
        $fullUrl = $this->baseUrl . $url;

        $options = [
            'headers' => $headers,
        ];

        if (!empty($body)) {
            if ($sendAsJson) {
                $options['json'] = $body;
            } else {
                $options['form_params'] = $body;
            }
        }

        try {
            return HttpRequestLogger::send($method, $fullUrl, $options);
        } catch (Throwable $e) {
            throw new ErrorException("Twilio request failed: " . $e->getMessage(), null,200);
        }
    }

    public function getCallDataBySid(string $sid)
    {
        $url = "/Accounts/{$this->accountSid}/Calls/{$sid}.json";
        return $this->sendRequest($url);
    }

    public function getCallRecording(string $sid)
    {
        $url = "/Accounts/{$this->accountSid}/Calls/{$sid}/Recordings.json";
        return $this->sendRequest($url);
    }

}
