<?php

return [
    'enabled' => [
        'github' => \Clarkeash\Shield\Services\GitHub::class,
        'gitlab' => \Clarkeash\Shield\Services\GitLab::class,
        'stripe' => \Clarkeash\Shield\Services\Stripe::class,
        'zapier' => \Clarkeash\Shield\Services\Zapier::class,
    ],

    'services' => [
        'github' => [
            'token' => 'your-custom-webhook-token'
        ],
        'gitlab' => [
            'token' => 'your-custom-webhook-token'
        ],
        'stripe' => [
            'token' => 'your-custom-webhook-token',
            'tolerance' => \Carbon\Carbon::SECONDS_PER_MINUTE * 5
        ],
        'zapier' => [
            'username' => 'your-basic-auth-user',
            'password' => 'your-basic-auth-password',
        ]
    ]
];
