## Spryng SMS Notification Channel for laravel

This repository provide the the laravel notification channel to send sms via Spryng sms.

### Installtion
```bash
composer require oscar-team/spryngsms
```

### Publish config file
```bash
php artisan vendor:publish --tag=spryngsms-config
```

```spryngsms.php``` config file will look like this. You can configure different options here. More info https://docs.spryngsms.com/#9-simple-http-api

```php
return [
  'token' => env('SPRYNG_SMS_API_TOKEN'),
  'originator' => env('SPRYNG_SMS_FROM_NAME', 'Oscar'),
  'route' => env('SPRYNG_SMS_ROUTE', 'business'),
  'encoding' => env('SPRYNG_SMS_ENCODING', 'auto'),
  'reference' => env('SPRYNG_SMS_REFERENCE')
];
```

### How to use?

```php
use Oscar\Spryngsms\SpryngsmsChannel;
use Oscar\Spryngsms\SpryngsmsMessage;

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
```

```SpryngsmsMessage::class``` look like this
```php
namespace Oscar\Spryngsms;

class SpryngsmsMessage
{
    public function __construct(
        public string  $body,
        public array   $recipients = [],
        public ?string $originator = null,
        public ?string $encoding = null,
        public ?string $route = null,
        public ?string $reference = null,
    ) {}
}
```

