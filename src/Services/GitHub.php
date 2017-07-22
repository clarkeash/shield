<?php

namespace Clarkeash\Shield\Services;

use Clarkeash\Shield\Contracts\Service;
use Illuminate\Http\Request;

class GitHub implements Service
{
    public function verify(Request $request): bool
    {
        $generated = 'sha1=' . hash_hmac('sha1', $request->getContent(), config('shield.services.github.token'));

        return hash_equals($generated, $request->header('X-Hub-Signature', ''));
    }
}
