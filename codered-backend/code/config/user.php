<?php

return [
    'module_name' => 'User',

    'instructor_dashboard' => env('INSTRUCTOR_URL'),
    'user_website' => env('USER_URL'),
    'user_login_as' => env('USER_LOGINAS_URL'),
    'instructor_login_as' => env('INSTRUCTOR_LOGINAS_URL'),

    'linkedin' => [
        'client_id'    => env('LINKEDIN_CLIENT_ID'),
        'client_secret'    => env('LINKEDIN_SECRET_ID'),
    ],
    'services' => [
        'update_password' => [
            'base_url' => env('UPDATE_PASSOWRD'),
        ],

        'ipstack' => [
            'access_key' => env('IPSTACK_ACCESS_KEY'),
            'base_url' => env('IPSTACK_BASE_URL'),
        ]
    ]
];
