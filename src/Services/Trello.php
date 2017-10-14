<?php

namespace Clarkeash\Shield\Services;

use Illuminate\Http\Request;

class Trello extends BaseService
{
    public function verify(Request $request): bool
    {
        $secret = config('shield.services.trello.app_secret');

        $content = trim($request->getContent()) . $request->fullUrl();

        $signature = hash_hmac('sha1', $content, $secret, true);

        return base64_encode($signature) === $request->header('X-Trello-Webhook');
    }

    public function headers(): array
    {
        return ['X-Trello-Webhook'];
    }
}