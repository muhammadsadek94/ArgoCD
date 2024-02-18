<?php

return [
    'middleware'       => ['admin.auth'],
    'route_path'       => \App\Foundation\Enum\Constants::ADMIN_BASE_URL.'/user-activity',
    'admin_panel_path' => \App\Foundation\Enum\Constants::ADMIN_BASE_URL.'/dashboard',
    'delete_limit'     => 365,

    'log_events' => [
        'on_edit'    => true,
        'on_delete'  => true,
        'on_create'  => true,
        'on_login'   => true,
        'on_lockout' => true
    ]
];
