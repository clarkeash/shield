<?php

namespace Clarkeash\Shield\Services;

use Illuminate\Http\Request;

class FacebookMessenger extends BaseService
{
    public function verify(Request $request): bool
    {
        $generated = 'sha1=' . hash_hmac('sha1', $request->getContent(), config('shield.services.facebook-messenger.app_secret'));

        return hash_equals($generated, $this->header($request, 'X-Hub-Signature'));
    }

    public function headers(): array
    {
        return ['X-Hub-Signature'];
    }
}
