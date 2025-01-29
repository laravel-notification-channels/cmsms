<?php

namespace NotificationChannels\Cmsms\Events;

use Illuminate\Foundation\Events\Dispatchable;

class SMSSendingFailedEvent
{
    use Dispatchable;

    public function __construct(public string $response)
    {
    }
}
