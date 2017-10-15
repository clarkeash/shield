<?php

return [
    'enabled' => [
        'braintree' => \Clarkeash\Shield\Services\Braintree::class,
        'github' => \Clarkeash\Shield\Services\GitHub::class,
        'gitlab' => \Clarkeash\Shield\Services\GitLab::class,
        'stripe' => \Clarkeash\Shield\Services\Stripe::class,
        'zapier' => \Clarkeash\Shield\Services\Zapier::class,
        'trello' => \Clarkeash\Shield\Services\Trello::class,
        'bitbucket' => \Clarkeash\Shield\Services\Bitbucket::class
    ],

    'services' => [
        'braintree' => [
            'environment' => 'development',
            'merchant_id' => 'your-merchant-id',
            'public_key' => 'your-public-key',
            'private_key' => 'your-private-key',
        ],
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
        ],
        'trello' => [
            'app_secret' => 'your-app-secret'
        ],
        'bitbucket' => [
            'allowed_ips' => [
                '104.192.143.0/24',
                '34.198.203.127',
                '34.198.178.64',
                '34.198.32.85'
            ]
        ]
    ]
];
