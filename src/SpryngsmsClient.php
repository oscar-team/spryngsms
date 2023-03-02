<?php

namespace Oscar\Spryngsms;

use Oscar\Spryngsms\Exceptions\CouldNotSendSmsNotification;
use Spryng\SpryngRestApi\Spryng;
use Spryng\SpryngRestApi\Objects\Message;

class SpryngsmsClient
{
    protected Spryng $client;

    public function __construct()
    {
        $this->client = new Spryng(config('spryngsms.token'));
    }

    /**
     * @throws CouldNotSendSmsNotification
     */
    public function send(SpryngsmsMessage $spryngsmsMessage): void
    {
        $message = new Message();
        $message->setBody($spryngsmsMessage->body);
        $message->setRecipients($spryngsmsMessage->recipients ?? []);
        $message->setOriginator($spryngsmsMessage->originator ?? config('spryngsms.originator'));
        $message->setEncoding($spryngsmsMessage->encoding ?? config('spryngsms.encoding'));
        $message->setRoute($spryngsmsMessage->route ?? config('spryngsms.route'));
        $message->setReference($spryngsmsMessage->reference ?? config('spryngsms.reference'));
        $response = $this->client->message->create($message);

        if (!$response->wasSuccessful()) {
            throw new CouldNotSendSmsNotification($response->getRawBody(), $response->getResponseCode());
        }
    }
}

