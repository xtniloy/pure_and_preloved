<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Active Currency
    |--------------------------------------------------------------------------
    |
    | The store currency, driven by the CURRENCY variable in your .env file.
    | Change it to any of the codes defined in the "symbols" map below and the
    | symbol updates everywhere prices are shown across the site.
    |
    */

    'code' => env('CURRENCY', 'GBP'),

    /*
    |--------------------------------------------------------------------------
    | Currency Symbols
    |--------------------------------------------------------------------------
    |
    | Maps a currency code to the symbol shown next to amounts. Add more codes
    | here as needed. If a code has no entry, the code itself is used as a
    | fallback prefix (e.g. "AED 10.00").
    |
    */

    'symbols' => [
        'USD' => '$',
        'GBP' => '£',
        'BDT' => '৳',
        'EUR' => '€',
        'INR' => '₹',
    ],

];
