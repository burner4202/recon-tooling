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


    'secret_key' => env('ADASH_SECRET', ($variables && array_key_exists('ADASH_SECRET', $variables)) ? $variables['ADASH_SECRET'] : null),


];
