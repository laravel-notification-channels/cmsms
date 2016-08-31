<?php

namespace NotificationChannels\Cmsms;

use NotificationChannels\Cmsms\Exceptions\InvalidMessage;

class CmsmsMessage
{
    /** @var string  */
    protected $body;

    /** @var string */
    protected $originator;

    /** @var string */
    protected $recipient;

    /** @var string */
    protected $reference;

    /**
     * @param string $body
     */
    public function __construct($body = '')
    {
        $this->body($body);
    }

    /**
     * @param string $body
     * @return $this
     */
    public function body($body)
    {
        $this->body = trim($body);

        return $this;
    }

    /**
     * @param string|int $originator
     * @return $this
     * @throws InvalidMessage
     */
    public function originator($originator)
    {
        if (empty($originator) || strlen($originator) > 11) {
            throw InvalidMessage::invalidOriginator($originator);
        }

        $this->originator = (string) $originator;

        return $this;
    }

    /**
     * @param string|int $recipient
     * @return $this
     */
    public function recipient($recipient)
    {
        $this->recipient = (string) $recipient;

        return $this;
    }

    /**
     * @param string $reference
     * @return $this
     * @throws InvalidMessage
     */
    public function reference($reference)
    {
        if (empty($reference) || strlen($reference) > 32 || !ctype_alnum($reference)) {
            throw InvalidMessage::invalidReference($reference);
        }

        $this->reference = $reference;

        return $this;
    }

    /**
     * @return array
     */
    public function toXmlArray()
    {
        return array_filter([
            'BODY' => $this->body,
            'FROM' => $this->originator,
            'TO' => $this->recipient,
            'REFERENCE' => $this->reference,
        ]);
    }

    /**
     * @param string $body
     * @return static
     */
    public static function create($body = '')
    {
        return new static($body);
    }
}
