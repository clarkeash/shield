<?php

namespace Clarkeash\Shield\Test\Services;

use Clarkeash\Shield\Contracts\Service;
use Clarkeash\Shield\Services\Zapier;
use Clarkeash\Shield\Test\TestCase;
use PHPUnit\Framework\Assert;

class ZapierTest extends TestCase
{
    /**
     * @var \Clarkeash\Shield\Services\Zapier
     */
    protected $service;

    protected function setUp()
    {
        parent::setUp();

        $this->service = new Zapier;
    }

    /** @test */
    public function it_is_a_service()
    {
        Assert::assertInstanceOf(Service::class, new Zapier);
    }

    /** @test */
    public function it_can_verify_a_valid_request()
    {
        $this->app['config']['shield.services.zapier.username'] = 'username';
        $this->app['config']['shield.services.zapier.password'] = 'password';

        $request = $this->request();

        $headers = [
            'PHP-AUTH-USER' => 'username',
            'PHP-AUTH-PW' => 'password',
        ];

        $request->headers->add($headers);

        Assert::assertTrue($this->service->verify($request));
    }

    /** @test */
    public function it_will_not_verify_a_bad_request()
    {
        $this->app['config']['shield.services.zapier.username'] = 'user';
        $this->app['config']['shield.services.zapier.password'] = 'pass';

        $request = $this->request();

        $headers = [
            'PHP-AUTH-USER' => 'user',
            'PHP-AUTH-PW' => 'wrong-pass',
        ];

        $request->headers->add($headers);

        Assert::assertFalse($this->service->verify($request));
    }

    /** @test */
    public function the_headers_are_not_important()
    {
        Assert::assertArraySubset([], $this->service->headers());
    }
}
