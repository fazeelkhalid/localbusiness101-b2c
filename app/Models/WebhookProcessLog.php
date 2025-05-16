<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebhookProcessLog extends Model
{
    use HasFactory;

    protected $table = 'webhook_process_logs';

    protected $fillable = [
        'webhook_log_id',
        'destination_url',
        'payload_sent',
        'response_headers',
        'response_body',
        'http_status_code',
        'error_message',
        'executed_at',
    ];

    protected $casts = [
        'payload_sent' => 'array',
        'response_headers' => 'array',
        'executed_at' => 'datetime',
    ];

    /**
     * Get the webhook log that owns this process log.
     */
    public function webhookLog()
    {
        return $this->belongsTo(WebhookLog::class, 'webhook_log_id');
    }
}
