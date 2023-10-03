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
    | EVE Driver Client ID
    |--------------------------------------------------------------------------
    |
    | Client ID for EVE SSO - As per Dev Page
    |
    */


    'client_id' => env('EVEONLINE_CLIENT_ID', ($variables && array_key_exists('EVEONLINE_CLIENT_ID', $variables)) ? $variables['EVEONLINE_CLIENT_ID'] : null),

    /*
    |--------------------------------------------------------------------------
    | Secret Key
    |--------------------------------------------------------------------------
    |
    | Secret Key as per Dev Page
    | 
    */


    'secret_key' => env('EVEONLINE_CLIENT_SECRET', ($variables && array_key_exists('EVEONLINE_CLIENT_SECRET', $variables)) ? $variables['EVEONLINE_CLIENT_SECRET'] : null),


];
