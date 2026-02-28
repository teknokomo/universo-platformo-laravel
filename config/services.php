<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Supabase, Mailgun, Postmark, AWS and more. This file provides the
    | de facto location for this type of information, allowing packages to
    | have a conventional file to locate the various service credentials.
    |
    */

    'supabase' => [
        'url'         => env('SUPABASE_URL'),
        'key'         => env('SUPABASE_KEY'),
        'service_key' => env('SUPABASE_SERVICE_KEY'),
    ],

];
