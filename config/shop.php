<?php

/*
|--------------------------------------------------------------------------
| TGC100 Merch Shop
|--------------------------------------------------------------------------
|
| Products, colors and stock live in the database and are managed from
| Admin > Inventory. This file holds the checkout settings.
|
*/

return [

    // Per cart line
    'max_quantity' => 10,

    // Estimated LBC Express door-to-door rates for a ~1kg package from Baguio City
    'shipping_areas' => [
        'pickup'   => ['label' => 'Pickup in Baguio City', 'fee' => 0],
        'ncr'      => ['label' => 'Metro Manila', 'fee' => 150],
        'luzon'    => ['label' => 'Luzon (outside Metro Manila)', 'fee' => 180],
        'visayas'  => ['label' => 'Visayas', 'fee' => 220],
        'mindanao' => ['label' => 'Mindanao', 'fee' => 240],
    ],

    'bank' => [
        'name'           => 'Rizal Commercial Banking Corporation (RCBC)',
        'account_name'   => 'RiCON',
        'account_number' => '7-591-41115-4',
    ],

    // Tracking number is appended
    'lbc_tracking_url' => 'https://www.lbcexpress.com/track/?tracking_no=',

    'contact_email' => 'riconph1@gmail.com',

];
