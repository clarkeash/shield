<?php

namespace Clarkeash\Shield\Services;

use Illuminate\Http\Request;

class GitHub extends BaseService
{
    public function verify(Request $request): bool
    {
        $generated = 'sha1=' . hash_hmac('sha1', $request->getContent(), config('shield.services.github.token'));

        return hash_equals($generated, $this->header($request, 'X-Hub-Signature'));
    }

    public function headers(): array
    {
        return ['X-Hub-Signature'];
    }
}
