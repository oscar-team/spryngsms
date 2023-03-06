## Spryng SMS Notification Channel for laravel

This repository provide the the laravel notification channel to send sms via Spryng sms.

### Installtion
```bash
composer require oscar-team/spryngsms
```

You can also publish the config file with:

```bash
php artisan vendor:publish --tag=spryngsms-config
```

```spryngsms.php``` You can find more info of config options here info https://docs.spryngsms.com/#9-simple-http-api

```php
return [
  'token' => env('SPRYNG_SMS_API_TOKEN'),
  'originator' => env('SPRYNG_SMS_FROM_NAME', 'Oscar'),
  'route' => env('SPRYNG_SMS_ROUTE', 'business'),
  'encoding' => env('SPRYNG_SMS_ENCODING', 'auto'),
  'reference' => env('SPRYNG_SMS_REFERENCE')
];
```

### Usage?


You can use the channel in your ```via()``` method inside the notification:


```php
use Oscar\Spryngsms\SpryngsmsChannel;
use Oscar\Spryngsms\SpryngsmsMessage;

class BookingNotification extends Notification
{
  public function via(object $notifiable): array
  {
      return [
        SpryngsmsMessage::class
      ];
  }

  public function toSpryngsms(mixed $notifiable): SpryngsmsMessage|string
  {
      return new SpryngsmsMessage($message, $recipients, $originator, $encoding, $route, $reference);
  }
}
```

Add a `routeNotificationForSpryngsms` method to your Notifiable model to return the phone number:

```php
public function routeNotificationForSpryngsms(): string
{
    return $this->phone_number;
}
```

Or add a `routeNotificationForSpryngsmsGroup` method to return the contacts group:

```php
public function routeNotificationForSmsapiGroup(): array
{
    return $this->contacts_group;
}
```

#### ```SpryngsmsMessage::class``` parameters
* ```$message``` (required)
* ```$recipients``` (optional)
* ```$originator``` (optional)
* ```$encoding``` (optional)
* ```$reference``` (optional)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
