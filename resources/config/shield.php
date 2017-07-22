<?php

return [
    'enabled' => [
        'github' => \Clarkeash\Shield\Services\GitHub::class
    ],

    'services' => [
        'github' => [
            'token' => 'your-custom-webhook-token'
        ]
    ]
];
