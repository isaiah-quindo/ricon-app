<?php

/*
|--------------------------------------------------------------------------
| TGC100 Merch Catalog
|--------------------------------------------------------------------------
|
| Products shown at /shop, keyed by URL slug. Prices are in PHP. `price` is
| the pre-order price charged on the order, `onsite_price` is shown struck
| through for comparison. `sizes` is null for one-size items.
|
*/

return [

    'max_quantity' => 10,

    'window' => 'September 25–30',

    'products' => [

        'trail-cap' => [
            'name'         => 'TGC100 Trail Cap',
            'short_name'   => 'Trail Cap',
            'heading'      => 'TGC100 Trail Cap',
            'intro'        => 'A lightweight 5-panel trail cap built for exposed ridgelines and long days in the sun, carrying the Cordillera graphic across the front panel.',
            'description'  => 'Sage green 5-panel trail cap with a curved brim, ripstop nylon crown, and "THE GREAT CORDILLERA 100" graphic across the front panel.',
            'price'        => 650,
            'onsite_price' => 750,
            'usd'          => 11,
            'specs'        => [
                'Fit'    => 'One size, adjustable strap',
                'Fabric' => 'Ripstop nylon, quick-dry',
                'Print'  => 'Front panel graphic, embroidered logo mark',
            ],
            'images'       => [
                'Front' => '/images/shop/cap.png',
            ],
            'sizes'        => null,
            'one_size_note' => 'The trail cap fits most head sizes, no size selection needed.',
        ],

        'finisher-hoodie' => [
            'name'         => 'TGC100 Finisher Hoodie',
            'short_name'   => 'Finisher Hoodie',
            'heading'      => 'Carry the Cordillera With You',
            'intro'        => 'A heavyweight fleece hoodie built for cold mornings at altitude, race-day layering, and everything after. Front and back, first look.',
            'description'  => 'Heather grey heavyweight fleece with the RiCON mark on the chest and a full Cordillera-pattern graphic across the back, "THE GREAT CORDILLERA 100" rendered in trail-inspired iconography.',
            'price'        => 1399,
            'onsite_price' => 1699,
            'usd'          => 24,
            'specs'        => [
                'Fit'    => 'Relaxed, unisex sizing',
                'Fabric' => 'Heavyweight fleece, brushed interior',
                'Print'  => 'Front chest logo, full back graphic',
                'Sizes'  => 'S through 3XL',
            ],
            'images'       => [
                'Front' => '/images/shop/hoodie-front.png',
                'Back'  => '/images/shop/hoodie-back.png',
            ],
            'sizes'        => ['S', 'M', 'L', 'XL', '2XL', '3XL'],
            // Asia fit, centimeters: [length, chest, shoulder, sleeve]
            'size_chart'   => [
                'S'   => [64, 54, 54, 53],
                'M'   => [66, 56, 56, 54],
                'L'   => [68, 58, 58, 55],
                'XL'  => [70, 60, 60, 56],
                '2XL' => [72, 62, 62, 56.5],
                '3XL' => [74, 64, 64, 57],
            ],
        ],

        'trail-tote' => [
            'name'         => 'TGC100 Trail Tote',
            'short_name'   => 'Trail Tote',
            'heading'      => 'TGC100 Trail Tote',
            'intro'        => 'A durable canvas tote for race day essentials, post-run errands, or just carrying the trail with you off the mountain.',
            'description'  => 'Forest green heavyweight canvas tote with the full Cordillera graphic printed front and center, built to hold up past race day.',
            'price'        => 399,
            'onsite_price' => 500,
            'usd'          => 7,
            'specs'        => [
                'Fit'    => 'One size, oversized carry',
                'Fabric' => 'Heavyweight canvas',
                'Print'  => 'Full front graphic',
            ],
            'images'       => [
                'Front' => '/images/shop/tote.png',
            ],
            'sizes'        => null,
            'one_size_note' => 'The trail tote is one size, no size selection needed.',
        ],

    ],

];
