<?php

namespace App\Http\Utils;

use App\Http\Mapper\AuthMapper;
use App\Models\BusinessProfile;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CustomUtils
{
    public static function getCountryFromIp($ip)
    {
        $client = new Client();
        $response = $client->get('http://ip-api.com/json/' . $ip);
        $data = json_decode($response->getBody(), true);

        return $data['country'] ?? 'Unknown';
    }

    public static function getBrowser($userAgent): string
    {
        if (str_contains($userAgent, 'Firefox')) {
            return 'Firefox';
        } elseif (str_contains($userAgent, 'Chrome')) {
            return 'Chrome';
        } elseif (str_contains($userAgent, 'Safari')) {
            return 'Safari';
        } elseif (str_contains($userAgent, 'MSIE') || str_contains($userAgent, 'Trident')) {
            return 'Internet Explorer';
        } else {
            return 'Unknown';
        }
    }

    public static function getDeviceType($userAgent): string
    {
        if (str_contains($userAgent, 'Mobile')) {
            return 'Mobile';
        } elseif (str_contains($userAgent, 'Tablet')) {
            return 'Tablet';
        } else {
            return 'Desktop';
        }
    }

    public static function isAssoc(array $arr)
    {
        if ([] === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public static function uploadProfileImage($folder='', $image, $filename)
    {
        $imagePath = $image->storeAs('images/business_profiles'.$folder, $filename, 'public');
        return Storage::url($imagePath);
    }


    public static function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $slugCount = BusinessProfile::where('slug', $slug)->count();

        if($slugCount){
            $profileCount= BusinessProfile::count();
            return $slug . '-' . ($profileCount);
        }
        return $slug;
    }

    public static function setMessageTraceUUID($response, $message_trace_uuid): void
    {
        $responseData = json_decode($response->getContent(), true);
        $responseData['message_trace_uuid'] = $message_trace_uuid;
        $response->setContent(json_encode($responseData));
    }

    public static function setMessageIfServerErrorOccur($response): void
    {
        if ($response->getStatusCode() >= 500 && !env('APP_DEBUG')) {
            $responseData = json_decode($response->getContent(), true);
            $responseData = AuthMapper::mapServerErrorResponseToAPIResponse($responseData);
            $response->setContent(json_encode($responseData));
        }
    }

}
