<?php

namespace Oscar\Spryngsms\Tests;

use Illuminate\Http\Client\RequestException;
use Mockery;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;
use Oscar\Spryngsms\Exceptions\CouldNotSendSmsNotification;
use Oscar\Spryngsms\SpryngsmsChannel;
use Oscar\Spryngsms\SpryngsmsClient;
use Oscar\Spryngsms\SpryngsmsMessage;
use Illuminate\Notifications\Notification;

class SpryngsmsChannelTest extends TestCase
{
    /**
     * @throws RequestException
     * @throws CouldNotSendSmsNotification
     */
    public function test_it_can_send_sms_with_message_object()
    {
        $message = new SpryngsmsMessage('message', [1234567]);
        $notifiable = new \stdClass();

        $this->createNotificationMock($notifiable, $message);
        $this->createSpryngClientMock($message);

        $channel = new SpryngsmsChannel(app(SpryngsmsClient::class));
        $channel->send($notifiable, app(Notification::class));
    }

    /**
     * @throws RequestException
     * @throws CouldNotSendSmsNotification
     */
    public function test_it_can_send_sms_if_message_string()
    {
        $message = 'message';

        $notifiable = new class{
            public int $phoneNumber = 12345678;
            public function routeNotificationForSpryngsms(): int
            {
                return $this->phoneNumber;
            }
        };;

        $this->createNotificationMock($notifiable, $message);
        $this->createSpryngClientMock(
            Mockery::on(fn ($arg) => $arg == new SpryngsmsMessage($message, [$notifiable->phoneNumber]))
        );

        $channel = new SpryngsmsChannel(app(SpryngsmsClient::class));
        $channel->send($notifiable, app(Notification::class));
    }

    public function test_it_can_send_sms_if_message_string_with_phone_number_array()
    {
        $message = 'message';

        $notifiable = new class{
            public array $phoneNumbers = [12345678];
            public function routeNotificationForSpryngsms(): array
            {
                return $this->phoneNumbers;
            }
        };;

        $this->createNotificationMock($notifiable, $message);
        $this->createSpryngClientMock(
            Mockery::on(fn ($arg) => $arg == new SpryngsmsMessage($message, $notifiable->phoneNumbers))
        );

        $channel = new SpryngsmsChannel(app(SpryngsmsClient::class));
        $channel->send($notifiable, app(Notification::class));
    }

    private function createNotificationMock(mixed $notifiable, string|SpryngsmsMessage $message)
    {
        $this->instance(
            Notification::class,
            Mockery::mock(Notification::class,
                fn(MockInterface $mock) => $mock->shouldReceive('toSpryngsms')
                    ->once()
                    ->with($notifiable)
                    ->andReturn($message)
            )
        );
    }

    private function createSpryngClientMock(mixed $params)
    {
        $this->instance(
            SpryngsmsClient::class,
            Mockery::mock(
                SpryngsmsClient::class,
                fn(MockInterface $mock) => $mock->shouldReceive('send')
                    ->once()
                    ->with($params)
            )
        );
    }
}