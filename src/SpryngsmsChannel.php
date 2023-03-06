<?php

namespace Oscar\Spryngsms;

use Illuminate\Http\Client\RequestException;
use Illuminate\Notifications\Notification;

class SpryngsmsChannel
{
    public function __construct(
        private SpryngsmsClient $client
    ) {
    }

    /**
     * @throws RequestException|Exceptions\CouldNotSendSmsNotification
     */
    public function send($notifiable, Notification $notification): void
    {
        $message = $notification->toSpryngsms($notifiable);

        if (is_string($message)) {
            $message = new SpryngsmsMessage($message);
        }

        if (empty($message->recipients)) {
            if (method_exists($notifiable, 'routeNotificationForSpryngsms')) {
                $message->recipients = [$notifiable->routeNotificationForSpryngsms()];
            } elseif (method_exists($notifiable, 'routeNotificationForSpryngsmsGroup')) {
                $message->recipients = $notifiable->routeNotificationForSpryngsmsGroup();
            }
        }

        $this->client->send($message);
    }
}
