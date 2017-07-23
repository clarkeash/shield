<?php

namespace Clarkeash\Shield\Services;

use Illuminate\Http\Request;

class Zapier extends BaseService
{
    public function verify(Request $request): bool
    {
        return $this->checkBasic($request, config('shield.services.zapier.username'), config('shield.services.zapier.password'));
    }

    public function headers(): array
    {
        return [];
    }
}
