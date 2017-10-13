<?php

return [
    'enabled' => [
        'braintree' => \Clarkeash\Shield\Services\Braintree::class,
        'github' => \Clarkeash\Shield\Services\GitHub::class,
        'gitlab' => \Clarkeash\Shield\Services\GitLab::class,
        'stripe' => \Clarkeash\Shield\Services\Stripe::class,
        'zapier' => \Clarkeash\Shield\Services\Zapier::class,
        'trello' => \Clarkeash\Shield\Services\Trello::class,
        'mailgun' => \Clarkeash\Shield\Services\Mailgun::class,
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
        'mailgun' => [
            'token' => env('MAILGUN_SECRET', 'your-custom-webhook-token'),
            'tolerance' => \Carbon\Carbon::SECONDS_PER_MINUTE * 5,
        ],
    ]
];
