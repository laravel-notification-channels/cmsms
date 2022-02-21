<?php

declare(strict_types=1);

namespace NotificationChannels\Cmsms;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Arr;
use NotificationChannels\Cmsms\Exceptions\CouldNotSendNotification;
use SimpleXMLElement;

class CmsmsClient
{
    const GATEWAY_URL = 'https://sgw01.cm.nl/gateway.ashx';

    public function __construct(
        protected GuzzleClient $client,
        protected string $productToken,
    ) {
    }

    public function send(CmsmsMessage $message, string $recipient): void
    {
        if (is_null(Arr::get($message->toXmlArray(), 'FROM'))) {
            $message->originator(config('services.cmsms.originator'));
        }

        $response = $this->client->request('POST', static::GATEWAY_URL, [
            'body' => $this->buildMessageXml($message, $recipient),
            'headers' => [
                'Content-Type' => 'application/xml',
            ],
        ]);

        // API returns an empty string on success
        // On failure, only the error string is passed
        $body = $response->getBody()->getContents();
        if (! empty($body)) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($body);
        }
    }

    public function buildMessageXml(CmsmsMessage $message, string $recipient): string
    {
        $xml = new SimpleXMLElement('<MESSAGES/>');

        $xml->addChild('AUTHENTICATION')
            ->addChild('PRODUCTTOKEN', $this->productToken);

        if ($tariff = $message->getTariff()) {
            $xml->addChild('TARIFF', (string) $tariff);
        }

        $msg = $xml->addChild('MSG');
        foreach ($message->toXmlArray() as $name => $value) {
            $msg->addChild($name, (string) $value);
        }
        $msg->addChild('TO', $recipient);

        return $xml->asXML();
    }
}
