<?php

namespace Clarkeash\Shield\Services;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;

class Bitbucket extends BaseService
{
    public function verify(Request $request): bool
    {
        return IpUtils::checkIp($request->ip(), config('shield.services.bitbucket.allowed_ips'));
    }

    public function headers(): array
    {
        return [];
    }
}
