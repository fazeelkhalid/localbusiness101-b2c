<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebhookLog extends Model
{
    use HasFactory;

    protected $table = 'webhook_logs';

    protected $fillable = [
        'service_name',
        'request_headers',
        'request_payload',
        'received_at',
        'ip_address',
        'processed_at',
        'url',
        'status',
    ];

    protected $casts = [
        'request_headers' => 'array',
        'request_payload' => 'array',
        'received_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    /**
     * Get the process logs associated with the webhook.
     */
    public function processLogs()
    {
        return $this->hasMany(WebhookProcessLog::class, 'webhook_log_id');
    }

    public static function logWebhook(array $data): self
    {
        return self::create([
            'service_name'     => $data['service_name'] ?? 'Unknown',
            'request_headers'  => $data['request_headers'] ?? [],
            'request_payload'          => $data['request_payload'] ?? [],
            'received_at'      => $data['received_at'] ?? now(),
            'ip_address'       => $data['ip_address'] ?? request()->ip(),
            'processed_at'     => $data['processed_at'] ?? null,
            'url'              => $data['url'] ?? request()->fullUrl(),
            'status'           => $data['status'] ?? 'Pending',
        ]);
    }
}
