<?php

declare(strict_types=1);

namespace NotificationChannels\Cmsms;

use Illuminate\Notifications\Notification;

class CmsmsChannel
{
    /** @var CmsmsClient */
    protected $client;

    /**
     * @param CmsmsClient $client
     */
    public function __construct(CmsmsClient $client)
    {
        $this->client = $client;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\Cmsms\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $recipient = $notifiable->routeNotificationFor('Cmsms')) {
            return;
        }

        $message = $notification->toCmsms($notifiable);

        if (is_string($message)) {
            $message = CmsmsMessage::create($message);
        }

        $this->client->send($message, $recipient);
    }
}
