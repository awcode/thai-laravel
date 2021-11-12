<?php

return [
    'default_language' => env('ADDRESS_LANG', 'en'),
    'whereinth_api_key' => env('WHEREIN_API_KEY', ''),
    'whereinth_api_secret' => env('WHEREIN_API_SECRET', ''),
    
    'format_precision_small' => env('FORMAT_PRECISION_S', 0),
    'format_precision_large' => env('FORMAT_PRECISION_L', 1)
];
