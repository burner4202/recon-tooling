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
    | Secret Key
    |--------------------------------------------------------------------------
    |
    | Secret Key
    | 
    */


    'webhook_url' => env('DISCORD_WEBHOOK', ($variables && array_key_exists('DISCORD_WEBHOOK', $variables)) ? $variables['DISCORD_WEBHOOK'] : null),


];
