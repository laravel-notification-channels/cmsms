<?php

declare(strict_types=1);

namespace NotificationChannels\Cmsms;

use NotificationChannels\Cmsms\Exceptions\InvalidMessage;

class CmsmsMessage
{
    /** @var string */
    protected $body;

    /** @var string */
    protected $originator;

    /** @var string */
    protected $reference;

    /** @var int */
    protected $tariff = 0;

    /** @var int */
    protected $minimumNumberOfMessageParts;

    /** @var int */
    protected $maximumNumberOfMessageParts;

    /**
     * @param string $body
     */
    private function __construct(string $body = "")
    {
        $this->body($body);
    }

    /**
     * @param string $body
     * @return $this
     */
    public function body(string $body)
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
     * @param string $reference
     * @return $this
     * @throws InvalidMessage
     */
    public function reference(string $reference)
    {
        if (empty($reference) || strlen($reference) > 32 || ! ctype_alnum($reference)) {
            throw InvalidMessage::invalidReference($reference);
        }

        $this->reference = $reference;

        return $this;
    }

    /**
     * @param int $tariff Tariff in eurocent
     * @return $this
     * @throws InvalidMessage
     */
    public function tariff(int $tariff)
    {
        $this->tariff = $tariff;

        return $this;
    }

    /**
     * @return int
     */
    public function getTariff(): int
    {
        return $this->tariff;
    }

    /**
     * @param int $minimum
     * @param int $maximum
     * @return $this
     * @throws InvalidMessage
     */
    public function multipart(int $minimum, int $maximum)
    {
        if ($maximum > 8 || $minimum >= $maximum) {
            throw InvalidMessage::invalidMultipart($minimum, $maximum);
        }

        $this->minimumNumberOfMessageParts = $minimum;
        $this->maximumNumberOfMessageParts = $maximum;

        return $this;
    }

    /**
     * @return array
     */
    public function toXmlArray(): array
    {
        return array_filter([
            'BODY' => $this->body,
            'FROM' => $this->originator,
            'REFERENCE' => $this->reference,
            'MINIMUMNUMBEROFMESSAGEPARTS' => $this->minimumNumberOfMessageParts,
            'MAXIMUMNUMBEROFMESSAGEPARTS' => $this->maximumNumberOfMessageParts,
        ]);
    }

    /**
     * @param string $body
     * @return static
     */
    public static function create($body = ''): self
    {
        return new static($body);
    }
}
