<?php
namespace App\Http\Services;

use App\Http\Mapper\UserBusinessProfileMapper;
use App\Http\Responses\ClientLogs\CreateClientLogsResponse;
use App\Http\Responses\UserBusinessProfile\GetBusinessProfileStateResponses;
use App\Http\Utils\CustomUtils;
use App\Models\ClientLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientLogsService
{

    protected AcquirerService $acquirerService;

    public function __construct(AcquirerService $acquirerService)
    {
        $this->acquirerService = $acquirerService;
    }

    public function clientLogs(Request $request)
    {
        $data = [
            'business_profile_id' => $this->acquirerService->get("businessProfile")->id,
            'country' => CustomUtils::getCountryFromIp($request->ip()),
            'browser' => CustomUtils::getBrowser($request->header('User-Agent')),
            'device_type' => CustomUtils::getDeviceType($request->header('User-Agent')),
            'referrer_url' => $request->headers->get('referer', 'Unknown'),
            'ip_address' => $request->ip()
        ];
        ClientLog::create($data);
        return new CreateClientLogsResponse("Client log successfully created.", 200);
    }

    public function fetchBusinessProfileState()
    {
        $businessProfileId = $this->acquirerService->get('businessProfile')->id;
        $clientLogs = ClientLog::where('business_profile_id', $businessProfileId)->get();

        $countryCount = [];
        $browserCount = [];
        $deviceTypeCount = [];
        $perDayUserCount = [];

        foreach ($clientLogs as $log) {
            if (isset($countryCount[$log->country])) {
                $countryCount[$log->country]++;
            } else {
                $countryCount[$log->country] = 1;
            }

            if (isset($browserCount[$log->browser])) {
                $browserCount[$log->browser]++;
            } else {
                $browserCount[$log->browser] = 1;
            }

            if (isset($deviceTypeCount[$log->device_type])) {
                $deviceTypeCount[$log->device_type]++;
            } else {
                $deviceTypeCount[$log->device_type] = 1;
            }

            $date = $log->created_at->toDateString();
            if (isset($perDayUserCount[$date])) {
                $perDayUserCount[$date]++;
            } else {
                $perDayUserCount[$date] = 1;
            }
        }

        $totalCountryCount = array_sum($countryCount);
        $totalBrowserCount = array_sum($browserCount);
        $totalDeviceTypeCount = array_sum($deviceTypeCount);
        $totalUserCount = array_sum($perDayUserCount);

        $countryCount['total'] = $totalCountryCount;
        $browserCount['total'] = $totalBrowserCount;
        $deviceTypeCount['total'] = $totalDeviceTypeCount;
        $perDayUserCount['total'] = $totalUserCount;

        $businessProfileStats = UserBusinessProfileMapper::mapDBStatetoReponse($countryCount, $browserCount, $deviceTypeCount, $perDayUserCount);
        return new GetBusinessProfileStateResponses($businessProfileStats, 200);
    }
}
