<?php

return [
    'module_name' => 'Course',
    'services' => [
        'ilab' => [
            'api_token' => env('ILAB_API_TOKEN'),

        ],
        'vimeo' => [
            'client_id' => env('VIMEO_CLIENT_ID'),
            'client_secret' => env('VIMEO_CLIENT_SECRET'),
            'access_token' => env('VIMEO_ACCESS_TOKEN'),

        ],
        'cyberq' => [
            'access_token' => env('CYBERQ_API_TOKEN'),
            'api_token' => env('CYBERQ_API_TOKEN'),
            'end_point' => env('CYBERQ_END_POINT'),
        ],

        'vital_source' => [
            'api_token' => env('VITAL_SOURCE_API_TOKEN'),
        ],

        'final_assessment' => [
            'time' => env('FINAL_ASSESSMENT_TIMER'),
        ]
    ]
];
