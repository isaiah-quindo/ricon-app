<?php

/*
|--------------------------------------------------------------------------
| Live Checkpoint Tracking
|--------------------------------------------------------------------------
|
| Shown at /live/{slug}. Only the 100K and 60K pass a timed checkpoint, so
| only they are tracked. Station details (km, cutoff, crew access) come from
| config/courses.php; this file only says which stations are timed, what
| short name spectators see for each, and who the runners are.
|
| `race_category` is the slug of the race category in admin. Its approved
| registrations with a bib number are the runners.
|
| `checkpoints` maps an aid station code from config/courses.php to its short
| name. Order follows the course. A name can repeat when the course passes the
| same place twice; the second pass is labelled "(2nd pass)" automatically.
|
| `finish_km` is the estimated race distance (the "Est. Distance" on the
| route page). The finish cutoff comes from `finish_cutoff` in courses.php.
|
*/

return [

    // Every race time on the live pages is shown in race-day local time.
    'timezone' => 'Asia/Manila',

    // Where the live pages keep the runner list for a few seconds between database reads.
    // "file" means a cache hit costs no database query, which matters when every open
    // page polls every 20 seconds.
    'cache_store' => env('LIVE_CACHE_STORE', 'file'),

    'races' => [

        '100km' => [
            'race_category' => 'tgc-100',
            'finish_km'     => 104,
            'checkpoints'   => [
                'AS1'  => 'Poblacion',
                'AS2'  => 'Dalupirip',
                'AS6'  => 'Dalupirip',
                'AS8'  => 'Kotkot',
                'AS9'  => 'Santa Fe',
                'AS10' => 'Ampucao Brgy Hall',
                'AS11' => 'Poblacion',
            ],
        ],

        '60km' => [
            'race_category' => 'tgc-60',
            'finish_km'     => 63,
            'checkpoints'   => [
                'AS1' => 'Poblacion',
                'AS3' => 'Kotkot',
                'AS4' => 'Santa Fe',
                'AS5' => 'Ampucao Brgy Hall',
                'AS6' => 'Poblacion',
            ],
        ],

    ],

];
