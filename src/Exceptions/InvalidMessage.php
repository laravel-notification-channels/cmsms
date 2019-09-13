<?php

namespace NotificationChannels\Cmsms\Exceptions;

use Exception;

class InvalidMessage extends Exception
{
    public static function invalidReference(string $reference): self
    {
        return new static("The reference on the CMSMS message may only contain 1 - 32 alphanumeric characters. Was given '{$reference}'");
    }

    public static function invalidOriginator(string $originator): self
    {
        return new static("The originator on the CMSMS message may only contain 1 - 11 characters. Was given '{$originator}'");
    }

    public static function invalidMultipart(int $minimum, int $maximum): self
    {
        return new static("The number of message parts for sending a multipart message on the CMSMS message may only contain a integer range from 0 to 8. Was given a minimum of '{$minimum}' and maximum of '{$maximum}'");
    }
}
