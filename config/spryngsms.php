<?php
return [
    'token' => env('SPRYNG_SMS_API_TOKEN'),
    'originator' => env('SPRYNG_SMS_FROM_NAME', 'Oscar'),
    'route' => env('SPRYNG_SMS_ROUTE', 'business'),
    'encoding' => env('SPRYNG_SMS_ENCODING', 'auto'),
    'reference' => env('SPRYNG_SMS_REFERENCE')
];
