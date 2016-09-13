<?php

namespace NotificationChannels\Cmsms;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Arr;
use NotificationChannels\Cmsms\Exceptions\CouldNotSendNotification;
use SimpleXMLElement;

class CmsmsClient
{
    const GATEWAY_URL = 'https://sgw01.cm.nl/gateway.ashx';

    /** @var GuzzleClient */
    protected $client;

    /** @var string */
    protected $productToken;

    /**
     * @param GuzzleClient $client
     * @param string $productToken
     */
    public function __construct(GuzzleClient $client, $productToken)
    {
        $this->client = $client;
        $this->productToken = $productToken;
    }

    /**
     * @param CmsmsMessage $message
     * @param string $recipient
     * @throws CouldNotSendNotification
     */
    public function send(CmsmsMessage $message, $recipient)
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

    /**
     * @param CmsmsMessage $message
     * @param string $recipient
     * @return string
     */
    public function buildMessageXml(CmsmsMessage $message, $recipient)
    {
        $xml = new SimpleXMLElement('<MESSAGES/>');

        $xml->addChild('AUTHENTICATION')
            ->addChild('PRODUCTTOKEN', $this->productToken);

        if ($tariff = $message->getTariff()) {
            $xml->addChild('TARIFF', $tariff);
        }

        $msg = $xml->addChild('MSG');
        foreach ($message->toXmlArray() as $name => $value) {
            $msg->addChild($name, $value);
        }
        $msg->addChild('TO', $recipient);

        return $xml->asXML();
    }
}
