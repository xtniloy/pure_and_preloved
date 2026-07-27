<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Search Engine Indexing
    |--------------------------------------------------------------------------
    |
    | Master on/off switch for SEO, driven by SEO_INDEXING in your .env file.
    |
    |   SEO_INDEXING=true   -> pages emit  <meta name="robots" content="index, follow">
    |   SEO_INDEXING=false  -> pages emit  <meta name="robots" content="noindex, nofollow">
    |
    | Defaults to false so the site stays hidden from crawlers until you
    | explicitly turn it on (e.g. when going live in production).
    |
    */

    'indexing' => env('SEO_INDEXING', false),

];
