<?php

return [
    'enabled' => [
        'github' => \Clarkeash\Shield\Services\GitHub::class,
        'stripe' => \Clarkeash\Shield\Services\Stripe::class,
    ],

    'services' => [
        'github' => [
            'token' => 'your-custom-webhook-token'
        ],
        'stripe' => [
            'token' => 'your-custom-webhook-token',
            'tolerance' => \Carbon\Carbon::SECONDS_PER_MINUTE * 5
        ]
    ]
];
