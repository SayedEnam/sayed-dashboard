<?php

return [
    'prefix' => 'dashboard',
    'middleware' => ['web', 'auth'],
    'navigation' => [
        [
            'title' => 'Dashboard',
            'icon' => 'home',
            'route' => 'sayed.dashboard.home',
        ],
    ],
];