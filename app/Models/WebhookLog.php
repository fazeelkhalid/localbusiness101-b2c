<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebhookLog extends Model
{
    use HasFactory;

    protected $table = 'webhook_logs';

    protected $fillable = [
        'request_headers',
        'payload',
        'received_at',
        'ip_address',
        'processed_at',
        'url',
        'status',
    ];

    protected $casts = [
        'request_headers' => 'array',
        'payload' => 'array',
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
}
