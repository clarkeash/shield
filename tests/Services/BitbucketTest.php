<?php

namespace Clarkeash\Shield\Test\Services;

use Clarkeash\Shield\Contracts\Service;
use Clarkeash\Shield\Services\Bitbucket;
use Clarkeash\Shield\Test\TestCase;
use PHPUnit\Framework\Assert;

class BitbucketTest extends TestCase
{
    /**
     * @var Bitbucket
     */
    protected $service;

    protected function setUp()
    {
        parent::setUp();

        $this->service = new Bitbucket;
    }

    /** @test */
    public function it_is_a_service()
    {
        Assert::assertInstanceOf(Service::class, new Bitbucket);
    }

    /** @test */
    public function it_can_verify_a_valid_request()
    {
        $allowedIps = config('shield.services.bitbucket.allowed_ips');
        $content = 'sample content';

        foreach ($allowedIps as $ip) {
            $request = $this->request($content, explode('/', $ip)[0]);

            Assert::assertTrue($this->service->verify($request));
        }
    }

    /** @test */
    public function it_will_not_verify_a_bad_request()
    {
        $content = 'sample content';
        $request = $this->request($content, '8.8.8.8');

        Assert::assertFalse($this->service->verify($request));
    }
}
