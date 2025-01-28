<?php

declare(strict_types=1);

namespace NotificationChannels\Cmsms;

use NotificationChannels\Cmsms\Exceptions\InvalidMessage;

class CmsmsMessage
{
    protected string $originator = '';

    protected string $reference = '';

    protected int $tariff = 0;

    protected ?int $minimumNumberOfMessageParts = null;

    protected ?int $maximumNumberOfMessageParts = null;

    private function __construct(
        protected string $body = ''
    ) {
        $this->body($body);
    }

    public function body(string $body): self
    {
        $this->body = trim($body);

        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function originator(string|int $originator): self
    {
        if (empty($originator) || strlen($originator) > 11) {
            throw InvalidMessage::invalidOriginator($originator);
        }

        $this->originator = (string) $originator;

        return $this;
    }

    public function getOriginator(): string
    {
        return $this->originator;
    }

    public function reference(string $reference): self
    {
        if (empty($reference) || strlen($reference) > 32 || ! ctype_alnum($reference)) {
            throw InvalidMessage::invalidReference($reference);
        }

        $this->reference = $reference;

        return $this;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function tariff(int $tariff): self
    {
        $this->tariff = $tariff;

        return $this;
    }

    public function getTariff(): int
    {
        return $this->tariff;
    }

    public function multipart(int $minimum, int $maximum): self
    {
        if ($maximum > 8 || $minimum >= $maximum) {
            throw InvalidMessage::invalidMultipart($minimum, $maximum);
        }

        $this->minimumNumberOfMessageParts = $minimum;
        $this->maximumNumberOfMessageParts = $maximum;

        return $this;
    }

    public function getMinimumNumberOfMessageParts(): ?int
    {
        return $this->minimumNumberOfMessageParts;
    }

    public function getMaximumNumberOfMessageParts(): ?int
    {
        return $this->maximumNumberOfMessageParts;
    }

    public static function create(string $body = ''): self
    {
        return new static($body);
    }
}
