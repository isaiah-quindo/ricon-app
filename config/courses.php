<?php

/*
|--------------------------------------------------------------------------
| Race Course Route Maps
|--------------------------------------------------------------------------
|
| Shown at /race-category/{slug}/route, keyed by race category slug. The track
| itself lives in public/courses/{slug}.json, built from the GPX file with:
|
|   php artisan course:build path/to/TGC100K.gpx 100km
|
| `distance`, `elevation_gain`, `cutoff_time`, `race_date`, `gunstart` and
| `start_finish` fill the colored strip at the top of the route page. Keep them in
| sync with the same strip on the race category page. The elevation graph uses the
| GPX track, so its numbers can differ slightly. Optional: `distance_label`
| (defaults to "Distance"), and `cutoff_note` / `gunstart_note` for a small line
| under those values.
|
| Aid stations: `code` (AS = aid station, WS = water station), `name` and `km`
| (official distance from the start) are required. `lat`/`lng` pin the station
| exactly: it snaps to the nearest track point within a few km of `km`, so a
| place the course passes twice lands on the right pass. `cutoff`, `food` and
| `services` are optional; `intermediate_cutoff` flags the course's main cutoff.
|
| Source: The Great Cordillera 100 Course Plan (Aid Station Plan 100KM sheet).
|
*/

// One public Drive folder holds the GPX files for every distance. The QR code
// (public/images/gpx-qr-code.png) points to the same folder via a gqr.sh short link.
$gpx = [
    'gpx_url' => 'https://drive.google.com/drive/folders/1K4x-vAubwb-a0-hBSswoGsIe9aeUQxYN?usp=sharing',
    'gpx_qr'  => '/images/gpx-qr-code.png',
];

return [

    '100km' => [
        ...$gpx,
        'title'          => 'TGC 100 KM',
        'distance_label' => 'Est. Distance',
        'distance'       => '104 KM',
        'elevation_gain' => '6,200M D+',
        'cutoff_time'    => '32 hrs',
        'cutoff_note'    => '14 hrs at 55 km',
        'race_date'      => 'Nov 13, 2026',
        'gunstart'       => '11 PM',
        'gunstart_note'  => 'Friday · UTC+8',
        'start_finish'   => 'Baguio City',
        'accent'         => '#f97316',
        'provisional'    => true,
        'aid_stations'   => [
            ['code' => 'AS1',  'name' => 'Sitio Maligaya, Poblacion', 'km' => 11.65, 'lat' => 16.366718, 'lng' => 120.668021,
                'cutoff' => '1:00 AM Sat', 'food' => 'Sports drink, bananas, boiled egg', 'services' => ['Water', 'Medic', 'Crew access']],
            ['code' => 'AS2',  'name' => 'Dalupirip Barangay Hall', 'km' => 25.18, 'lat' => 16.326347, 'lng' => 120.723997,
                'cutoff' => '5:00 AM Sat', 'food' => 'Sports drink, soft drinks, bananas, watermelon, sweet potatoes', 'services' => ['Water', 'Medic', 'Crew access']],
            ['code' => 'WS3',  'name' => 'Palanchey', 'km' => 30.61, 'lat' => 16.315279, 'lng' => 120.755711,
                'cutoff' => '8:00 AM Sat', 'food' => 'Light snacks', 'services' => ['Water', 'Medic']],
            ['code' => 'AS4',  'name' => 'Buhao', 'km' => 37.70, 'lat' => 16.296085, 'lng' => 120.778837,
                'cutoff' => '10:00 AM Sat', 'services' => ['Water', 'Medic']],
            ['code' => 'WS5',  'name' => 'Sitio Bantic, Dalupirip', 'km' => 42.10, 'lat' => 16.267664, 'lng' => 120.764876,
                'cutoff' => '11:00 AM Sat', 'services' => ['Water']],
            ['code' => 'AS6',  'name' => 'Dalupirip Barangay Hall', 'km' => 53.67, 'lat' => 16.326347, 'lng' => 120.723997,
                'cutoff' => '1:00 PM Sat', 'intermediate_cutoff' => true, 'food' => 'Hot meal (rice porridge), noodles', 'services' => ['Water', 'Medic', 'Crew access', 'Drop bag']],
            ['code' => 'WS7',  'name' => 'Sitio Sayo, Dalupirip', 'km' => 58.76,
                'cutoff' => '3:00 PM Sat', 'food' => 'Sports drink, soft drinks, bananas, watermelon, sweet potatoes'],
            ['code' => 'AS8',  'name' => 'Mount Kotkot', 'km' => 62.80, 'lat' => 16.326532, 'lng' => 120.690114,
                'food' => 'Coffee, biscuits, energy gels', 'services' => ['Water', 'Medic']],
            ['code' => 'AS9',  'name' => 'Santa Fe, Ampucao', 'km' => 72.80, 'lat' => 16.286647, 'lng' => 120.643830,
                'cutoff' => '10:00 PM Sat', 'food' => 'Hot meal (rice porridge, pasta soup), sports drink, soft drinks, bananas, watermelon, sweet potatoes', 'services' => ['Water', 'Medic', 'Crew access']],
            ['code' => 'AS10', 'name' => 'Ampucao Barangay Hall', 'km' => 81.90, 'lat' => 16.325352, 'lng' => 120.654656,
                'cutoff' => '3:00 AM Sun', 'food' => 'Hot meal (rice porridge, pork nilaga), sports drink, soft drinks, coffee, ginger tea', 'services' => ['Water', 'Medic', 'Crew access']],
            ['code' => 'AS11', 'name' => 'Sitio Maligaya, Poblacion', 'km' => 91.43, 'lat' => 16.366718, 'lng' => 120.668021,
                'cutoff' => '4:30 AM Sun', 'food' => 'Hot meal (tinola, rice porridge), broth, energy bars, rice, sweet potatoes', 'services' => ['Water', 'Medic', 'Crew access']],
            ['code' => 'AS12', 'name' => 'Hekla, Gumatdang', 'km' => 96.90, 'lat' => 16.387660, 'lng' => 120.643665,
                'cutoff' => '5:30 AM Sun', 'food' => 'Broth, energy bars', 'services' => ['Water', 'Medic']],
            ['code' => 'AS13', 'name' => 'Happy Hollow', 'km' => 100.00, 'lat' => 16.400102, 'lng' => 120.625405,
                'cutoff' => '6:30 AM Sun', 'food' => 'Sports drink, soft drinks, bananas, watermelon, sweet potatoes, chocolates', 'services' => ['Water', 'Medic', 'Crew access']],
        ],
        'finish_cutoff'  => '7:00 AM Sun',
    ],

    // The 60K sheet has no coordinates; stations at places the 100K also passes reuse
    // the 100K coordinates. It lists no food or medic details yet.
    '60km' => [
        ...$gpx,
        'title'          => 'TGC 60 KM',
        'distance_label' => 'Est. Distance',
        'distance'       => '63 KM',
        'elevation_gain' => '3,500M D+',
        'cutoff_time'    => '18 hrs',
        'cutoff_note'    => '8 hrs at 31 km',
        'race_date'      => 'Nov 14, 2026',
        'gunstart'       => '3 AM',
        'gunstart_note'  => 'Saturday · UTC+8',
        'start_finish'   => 'Baguio City',
        'accent'         => '#ef4444',
        'provisional'    => true,
        'aid_stations'   => [
            ['code' => 'AS1', 'name' => 'Sitio Maligaya, Poblacion', 'km' => 11.65, 'lat' => 16.366718, 'lng' => 120.668021,
                'cutoff' => '5:00 AM Sat', 'services' => ['Water', 'Crew access']],
            ['code' => 'WS2', 'name' => 'Tokok Water Station', 'km' => 16.30,
                'services' => ['Water']],
            ['code' => 'AS3', 'name' => 'Mount Kotkot', 'km' => 21.00, 'lat' => 16.326532, 'lng' => 120.690114,
                'cutoff' => '9:00 AM Sat', 'services' => ['Water']],
            ['code' => 'AS4', 'name' => 'Santa Fe, Ampucao', 'km' => 30.81, 'lat' => 16.286647, 'lng' => 120.643830,
                'cutoff' => '11:00 AM Sat', 'intermediate_cutoff' => true, 'services' => ['Water']],
            ['code' => 'AS5', 'name' => 'Ampucao Barangay Hall', 'km' => 40.00, 'lat' => 16.325352, 'lng' => 120.654656,
                'cutoff' => '3:00 PM Sat', 'services' => ['Water', 'Crew access']],
            ['code' => 'AS6', 'name' => 'Sitio Maligaya, Poblacion', 'km' => 49.62, 'lat' => 16.366718, 'lng' => 120.668021,
                'cutoff' => '5:00 PM Sat', 'services' => ['Water', 'Crew access']],
            ['code' => 'AS7', 'name' => 'Hekla, Gumatdang', 'km' => 55.30, 'lat' => 16.387660, 'lng' => 120.643665,
                'cutoff' => '7:00 PM Sat', 'services' => ['Water']],
            ['code' => 'AS8', 'name' => 'Happy Hollow', 'km' => 58.40, 'lat' => 16.400102, 'lng' => 120.625405,
                'cutoff' => '8:30 PM Sat', 'services' => ['Water', 'Crew access']],
        ],
        'finish_cutoff'  => '9:00 PM Sat',
    ],

    // Codes are as written in the 21K sheet (AS1, AS2, WS1, AS3, AS5).
    '21km' => [
        ...$gpx,
        'title'          => 'TGC 21 KM',
        'distance_label' => 'Est. Distance',
        'distance'       => '22 KM',
        'elevation_gain' => '1,200M D+',
        'cutoff_time'    => '8 hrs',
        'cutoff_note'    => '6.5 hrs at 16 km',
        'race_date'      => 'Nov 15, 2026',
        'gunstart'       => '4:30 AM',
        'gunstart_note'  => 'Sunday · UTC+8',
        'start_finish'   => 'Baguio City',
        'accent'         => '#16a34a',
        'provisional'    => true,
        'aid_stations'   => [
            ['code' => 'AS1', 'name' => 'Happy Hollow', 'km' => 2.70, 'lat' => 16.400102, 'lng' => 120.625405],
            ['code' => 'AS2', 'name' => 'Gumatdang Elementary School', 'km' => 5.60, 'lat' => 16.391109, 'lng' => 120.642629,
                'food' => 'Electrolytes, bananas', 'services' => ['Water']],
            ['code' => 'WS1', 'name' => 'Tailings Dam, Gumatdang', 'km' => 12.00, 'lat' => 16.370959, 'lng' => 120.660513,
                'food' => 'Coffee, biscuits', 'services' => ['Water']],
            ['code' => 'AS3', 'name' => 'Hekla, Gumatdang', 'km' => 15.80, 'lat' => 16.387664, 'lng' => 120.643676,
                'cutoff' => '11:00 AM Sun', 'intermediate_cutoff' => true, 'services' => ['DNF pick-up']],
            ['code' => 'AS5', 'name' => 'Happy Hollow', 'km' => 18.88, 'lat' => 16.400102, 'lng' => 120.625405,
                'food' => 'Hot meals', 'services' => ['Water', 'First aid']],
        ],
        'finish_cutoff'  => '12:30 PM Sun',
    ],

    '10km' => [
        ...$gpx,
        'title'          => 'TGC 10 KM',
        'distance_label' => 'Est. Distance',
        'distance'       => '10 KM',
        'elevation_gain' => '400M D+',
        'cutoff_time'    => '3 hrs',
        'race_date'      => 'Nov 15, 2026',
        'gunstart'       => '6:30 AM',
        'gunstart_note'  => 'Sunday · UTC+8',
        'start_finish'   => 'Baguio City',
        'accent'         => '#0891b2',
        'provisional'    => true,
        'aid_stations'   => [
            ['code' => 'AS1', 'name' => 'View Deck', 'km' => 4.00, 'lat' => 16.383422, 'lng' => 120.621171,
                'cutoff' => '7:30 AM Sun', 'services' => ['Water']],
            ['code' => 'WS2', 'name' => 'Riding Circle', 'km' => 7.20, 'lat' => 16.395575, 'lng' => 120.613419,
                'food' => 'Electrolytes, bananas', 'services' => ['Water']],
            ['code' => 'WS3', 'name' => 'US Embassy Gate', 'km' => 8.60, 'lat' => 16.388877, 'lng' => 120.619170,
                'food' => 'Coffee, biscuits', 'services' => ['Water']],
        ],
        'finish_cutoff'  => '9:30 AM Sun',
    ],

];
