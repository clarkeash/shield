<?php

namespace Clarkeash\Shield\Services;

use Clarkeash\Shield\Contracts\Service;
use Illuminate\Http\Request;

abstract class BaseService implements Service
{
    public function header(Request $request, $name, $default = '')
    {
        return $request->headers->get($name, $default, true);

    }
}
