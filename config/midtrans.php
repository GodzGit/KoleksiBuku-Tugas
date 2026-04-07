<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Server Key
    |--------------------------------------------------------------------------
    */
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    
    /*
    |--------------------------------------------------------------------------
    | Midtrans Client Key
    |--------------------------------------------------------------------------
    */
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    
    /*
    |--------------------------------------------------------------------------
    | Is Production
    |--------------------------------------------------------------------------
    | true = Production (Real payment)
    | false = Sandbox (Testing, tidak bayar sungguhan)
    */
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    
    /*
    |--------------------------------------------------------------------------
    | Midtrans API URL
    |--------------------------------------------------------------------------
    */
    'api_url' => env('MIDTRANS_IS_PRODUCTION', false) 
        ? 'https://api.midtrans.com/v2'
        : 'https://api.sandbox.midtrans.com/v2',
    
    'snap_url' => env('MIDTRANS_IS_PRODUCTION', false)
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js',
];