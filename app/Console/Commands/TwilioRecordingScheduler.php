<?php

namespace App\Console\Commands;

use App\Http\Services\Client\TwilioHTTPHandler;
use App\Http\Utils\CustomUtils;
use App\Models\CallLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TwilioRecordingScheduler extends Command
{

    protected $signature = 'twilio:fetch-recordings';
    protected $description = 'Fetch and download call recordings from Twilio for records with recording SIDs but no URLs';
    protected $twilioClient;

    public function __construct(TwilioHTTPHandler $twilioClient)
    {
        parent::__construct();
        $this->twilioClient = $twilioClient;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting to fetch Twilio recordings...');

        $callLogs = CallLog::whereNotNull('twilio_recording_sid')->whereNull('recording_url')->get();

        $this->info("Found {$callLogs->count()} call logs to process");

        foreach ($callLogs as $callLog) {
            try {
                $callSid = $callLog->twilio_sid;
                $recordingSid = $callLog->twilio_recording_sid;
                $this->info("Processing call log ID: {$callLog->id}, Twilio SID: {$callSid}, Recording SID: {$recordingSid}");

                $response = $this->twilioClient->getTwilioMediaRecordingUrlBySid($callLog->twilio_recording_sid);
                Log::info("Twilio call recording Data:, {$response}");

                if($response->successful()){
                    $mp3Content = $response->body();
                    $recordingUrl = CustomUtils::storeMediaFile('recordings', "{$recordingSid}.mp3", $mp3Content);
                    $callLog->recording_url = $recordingUrl;
                    $callLog->save();
                }

            } catch (\Exception $e) {
                $this->error("Error processing call log ID: {$callLog->id} - " . $e->getMessage());
                Log::error("Error processing call log ID: {$callLog->id}", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'call_log' => $callLog->toArray()
                ]);
            }
        }
        return 0;
    }
}
