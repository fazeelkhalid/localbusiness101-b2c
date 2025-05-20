<?php

namespace App\Console\Commands;

use App\Enums\WebhookSenderTypeEnum;
use App\Enums\WebhookStatusEnum;
use App\Models\WebhookLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Webhook\Resolvers\WebhookServiceResolver;

class ProcessPendingTwilioWebhooks extends Command
{
    // Command signature used in the scheduler
    protected $signature = 'webhook:process-twilio';

    protected $description = 'Process pending Twilio webhooks';

    public function handle(): int
    {
        Log::info('ProcessPendingTwilioWebhooks command started.');

        $pendingWebhooks = WebhookLog::where('service_name', WebhookSenderTypeEnum::TWILIO)
            ->where('status', WebhookStatusEnum::PENDING)
            ->orderBy('received_at')
            ->get();

        Log::info('[ProcessPendingTwilioWebhooks] Pending webhooks count: ' . $pendingWebhooks->count());

        foreach ($pendingWebhooks as $webhookLog) {
            DB::beginTransaction();
            try {
                $webhookLog->updateStatus(WebhookStatusEnum::IN_PROGRESS);
                $webhookService = WebhookServiceResolver::resolveFromServiceName($webhookLog->service_name);
                $webhookService->execute($webhookLog);
                $webhookLog->updateStatus(WebhookStatusEnum::PROCESSED);
                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                Log::error('Webhook processing failed: ' . $e->getMessage());
                $webhookLog->updateStatus(WebhookStatusEnum::FAILED);
            }
        }

        Log::info('ProcessPendingTwilioWebhooks command finished.');

        return Command::SUCCESS;
    }
}
