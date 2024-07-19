<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiReqRespLog extends Model
{
    use HasFactory;

    protected $table = 'api_req_resp_logs';

    protected $fillable = [
        'message_trace_uuid',
        'request_header',
        'payload',
        'complete_url',
        'http_endpoint',
        'http_method',
        'http_status_code',
        'response_header',
        'response_time',
        'time_interval',
        'source_ip',
        'source_port',
        'response_body',
    ];

}
