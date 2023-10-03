<?php

    /*
    |--------------------------------------------------------------------------
    | Platform.sh configuration
    |--------------------------------------------------------------------------
    */

    $variables = json_decode(base64_decode(getenv("PLATFORM_VARIABLES")), true);

    return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
    	'domain' => env('MAILGUN_DOMAIN'),
    	'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
    	'key'    => env('SES_KEY'),
    	'secret' => env('SES_SECRET'),
    	'region' => 'us-east-1',
    ],

    'stripe' => [
    	'model'  => Vanguard\User::class,
    	'key'    => env('STRIPE_KEY'),
    	'secret' => env('STRIPE_SECRET'),
    ],

    'mailtrap' => [
    	'default_inbox' => '58948',
    	'secret' => env('MAILTRAP_SECRET')
    ],

    'facebook' => [
    	'client_id' => env('FACEBOOK_CLIENT_ID'),
    	'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    	'redirect' => env('FACEBOOK_CALLBACK_URI'),
    ],

    'twitter' => [
    	'client_id' => env('TWITTER_CLIENT_ID'),
    	'client_secret' => env('TWITTER_CLIENT_SECRET'),
    	'redirect' => env('TWITTER_CALLBACK_URI'),
    ],

    'google' => [
    	'client_id' => env('GOOGLE_CLIENT_ID'),
    	'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    	'redirect' => env('GOOGLE_CALLBACK_URI'),
    ],

    'eveonline' => [
    	'client_id' => env('EVEONLINE_CLIENT_ID', ($variables && array_key_exists('EVEONLINE_CLIENT_ID', $variables)) ? $variables['EVEONLINE_CLIENT_ID'] : null),
    	'client_secret' => env('EVEONLINE_CLIENT_SECRET', ($variables && array_key_exists('EVEONLINE_CLIENT_SECRET', $variables)) ? $variables['EVEONLINE_CLIENT_SECRET'] : null),
    	'redirect' => env('EVEONLINE_CALLBACK_URI', ($variables && array_key_exists('EVEONLINE_CALLBACK_URI', $variables)) ? $variables['EVEONLINE_CALLBACK_URI'] : null),
    ],

    'gice' => [
    	'client_id' => env('GICE_CLIENT_ID', ($variables && array_key_exists('GICE_CLIENT_ID', $variables)) ? $variables['GICE_CLIENT_ID'] : null),
    	'client_secret' => env('GICE_CLIENT_SECRET', ($variables && array_key_exists('GICE_CLIENT_SECRET', $variables)) ? $variables['GICE_CLIENT_SECRET'] : null),
    	'redirect' => env('GICE_CALLBACK_URI', ($variables && array_key_exists('GICE_CALLBACK_URI', $variables)) ? $variables['GICE_CALLBACK_URI'] : null),
    ],

];
