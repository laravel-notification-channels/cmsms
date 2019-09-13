<?php

namespace NotificationChannels\Cmsms\Exceptions;

use Exception;

class InvalidConfiguration extends Exception
{
    public static function configurationNotSet(): self
    {
        return new static('In order to send notifications via CMSMS you need to add credentials in the `cmsms` key of `config.services`.');
    }
}
