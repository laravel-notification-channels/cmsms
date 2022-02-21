<?php

declare(strict_types=1);

namespace NotificationChannels\Cmsms;

use Illuminate\Notifications\Notification;

class CmsmsChannel
{
    public function __construct(
        protected CmsmsClient $client,
    )
    {
    }

    public function send($notifiable, Notification $notification)
    {
        if (!$recipient = $notifiable->routeNotificationFor('Cmsms')) {
            return;
        }

        $message = $notification->toCmsms($notifiable);

        if (is_string($message)) {
            $message = CmsmsMessage::create($message);
        }

        $this->client->send($message, $recipient);
    }
}
