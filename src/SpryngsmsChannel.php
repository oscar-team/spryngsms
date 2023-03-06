<?php

namespace Oscar\Spryngsms;

use Illuminate\Http\Client\RequestException;
use Illuminate\Notifications\Notification;

class SpryngsmsChannel
{
    public function __construct(
        private SpryngsmsClient $client
    ) {}

    /**
     * @throws RequestException|Exceptions\CouldNotSendSmsNotification
     */
    public function send($notifiable, Notification $notification): void
    {
        $message = $notification->toSpryngsms($notifiable);

        if (is_string($message)) {
            $message = new SpryngsmsMessage($message);
        }

        if (empty($message->recipients) && method_exists($notifiable, 'routeNotificationForSpryngsms')) {
            $message->recipients = [$notifiable->routeNotificationForSpryngsms()];
        }

        if (empty($message->recipients)) {
            return;
        }

        $this->client->send($message);
    }
}
