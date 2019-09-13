<?php

namespace NotificationChannels\Cmsms\Exceptions;

use Exception;

class CouldNotSendNotification extends Exception
{
    public static function serviceRespondedWithAnError(string $error): self
    {
        return new static("CMSMS service responded with an error: {$error}'");
    }
}
