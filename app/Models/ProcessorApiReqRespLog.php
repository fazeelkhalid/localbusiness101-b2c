<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcessorApiReqRespLog extends Model
{
    protected $fillable = [
        'method',
        'url',
        'request_headers',
        'request_body',
        'http_status_code',
        'response_body',
        'exception',
        'requested_at',
        'responded_at',
    ];

    protected $casts = [
        'request_headers' => 'array',
        'request_body' => 'array',
        'response_body' => 'array',
    ];
}
