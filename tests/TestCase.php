<?php

namespace Clarkeash\Shield\Test;

use Clarkeash\Shield\Providers\ShieldServiceProvider;
use Illuminate\Http\Request;
use Orchestra\Testbench\TestCase as TestBench;

class TestCase extends TestBench
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            ShieldServiceProvider::class
        ];
    }

    /**
     * @param null $content
     *
     * @return Request
     */
    protected function request($content = null)
    {
        return Request::create('http://example.com', 'POST', [], [], [], [], $content);
    }
}
