<?php

namespace NotificationChannels\Cmsms\Events;

use Illuminate\Foundation\Events\Dispatchable;

class SMSSentSuccessfullyEvent
{
    use Dispatchable;

    public function __construct(public array $payload)
    {
    }
}
