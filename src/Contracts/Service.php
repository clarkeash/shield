<?php

namespace Clarkeash\Shield\Contracts;

use Illuminate\Http\Request;

interface Service
{
    public function verify(Request $request): bool;
}
