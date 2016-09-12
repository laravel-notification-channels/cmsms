<?php

namespace NotificationChannels\Cmsms\Exceptions;

use Exception;

class InvalidMessage extends Exception
{
    /**
     * @param string $reference
     * @return static
     */
    public static function invalidReference($reference)
    {
        return new static("The reference on the CMSMS message may only contain 1 - 32 alphanumeric characters. Was given '{$reference}'");
    }

    /**
     * @param string $originator
     * @return static
     */
    public static function invalidOriginator($originator)
    {
        return new static("The originator on the CMSMS message may only contain 1 - 11 characters. Was given '{$originator}'");
    }

    /**
     * @param int $tariff
     * @return static
     */
    public static function invalidTariff($tariff)
    {
        return new static("The tarrif on the CMSMS message may only contain a nonzero integer. Was given '{$tariff}'");
    }

    /**
     * @param int $minimum
     * @param int $maximum
     * @return static
     */
    public static function invalidMessageParts($minimum, $maximum)
    {
        return new static("The number of message parts on the CMSMS message may only contain a integer range from 0 to 8. Was given a minimum of '{$minimum}' and maximum of '{$maximum}'");
    }
}
