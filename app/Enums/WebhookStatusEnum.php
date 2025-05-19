<?php

namespace App\Enums;

enum WebhookStatusEnum: string
{
    case PENDING = 'Pending';
    case IN_PROGRESS = 'InProgress';
    case PROCESSED = 'Processed';
    case FAILED = 'Failed';
}
