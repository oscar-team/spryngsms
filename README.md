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
    /*
    |----------------------------------------------------------------------
    | Authentication token
    |----------------------------------------------------------------------
    |
    | Here we need to define the token for api authentication
    |
    */
    'token' => env('SPRYNG_SMS_API_TOKEN'),

    /*
    |----------------------------------------------------------------------
    | From / Originator
    |----------------------------------------------------------------------
    |
    | The sender of the message. Can be alphanumeric string (max. 11 characters)
    | or phonenumber (max. 14 digits in E.164 format like 31612345678)
    |
    */
    'originator' => env('SPRYNG_SMS_FROM_NAME', 'Oscar'),

    /*
    |----------------------------------------------------------------------
    | Route
    |----------------------------------------------------------------------
    |
    | Your given route to send the message on. Can be a valid route
    | ID supplied by Spryng or the default business route.
    |
    */
    'route' => env('SPRYNG_SMS_ROUTE', 'business'),

    /*
    |----------------------------------------------------------------------
    | Route
    |----------------------------------------------------------------------
    |
    | Character encoding of the body. Value can be: plain, unicode or auto
    |
    */
    'encoding' => env('SPRYNG_SMS_ENCODING', 'auto'),

    /*
    |----------------------------------------------------------------------
    | Reference
    |----------------------------------------------------------------------
    |
    | A client reference.
    |
    */
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

#### ```SpryngsmsMessage::class``` parameters
* ```$message``` (required)
* ```$recipients``` (optional)
* ```$originator``` (optional)
* ```$encoding``` (optional)
* ```$reference``` (optional)

## Tests

```phpunit tests```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
