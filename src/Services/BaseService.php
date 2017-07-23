<?php

namespace Clarkeash\Shield\Services;

use Clarkeash\Shield\Contracts\Service;
use Illuminate\Http\Request;

abstract class BaseService implements Service
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param                          $name
     * @param string                   $default
     *
     * @return string
     */
    public function header(Request $request, $name, $default = '')
    {
        return $request->headers->get($name, $default, true);

    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string                   $username
     * @param string                   $password
     *
     * @return bool
     */
    public function checkBasic(Request $request, string $username, string $password): bool
    {
        if ($request->hasHeader('PHP-AUTH-USER') && $request->hasHeader('PHP-AUTH-PW')) {
            return $request->header('PHP-AUTH-USER') == $username && $request->header('PHP-AUTH-PW') == $password;
        }

        return false;
    }
}
