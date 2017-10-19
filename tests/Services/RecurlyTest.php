<?php

namespace Clarkeash\Shield\Test\Services;

use Clarkeash\Shield\Contracts\Service;
use Clarkeash\Shield\Services\Recurly;
use Clarkeash\Shield\Test\TestCase;
use PHPUnit\Framework\Assert;

class RecurlyTest extends TestCase
{
    /**
     * @var \Clarkeash\Shield\Services\Recurly
     */
    protected $service;

    protected function setUp()
    {
        parent::setUp();

        $this->service = new Recurly;
    }

    /** @test */
    public function it_is_a_service()
    {
        Assert::assertInstanceOf(Service::class, new Recurly);
    }

    /** @test */
    public function it_can_verify_a_valid_request()
    {

        $token = 'dXNlcm5hbWU6cGFzc3dvcmQ=';

        $this->app['config']['shield.services.recurly.username'] = 'username';
        $this->app['config']['shield.services.recurly.password'] = 'password';
      
        $request = $this->request();

        $headers = [
            'Authorization' => 'Bearer ' . $token
        ];

        $request->headers->add($headers);

        Assert::assertTrue($this->service->verify($request));
    }

    /** @test */
    public function it_will_not_verify_a_bad_request()
    {
        $token = 'dXNlcm5hbWU6cGFzcw==';
        
        $this->app['config']['shield.services.recurly.username'] = 'username';
        $this->app['config']['shield.services.recurly.password'] = 'password';

        $request = $this->request();

        $headers = [
            'Authorization' => 'Bearer ' . $token
        ];

        $request->headers->add($headers);

        Assert::assertFalse($this->service->verify($request));
    }
   

    /** @test */
    public function it_has_correct_headers_required()
    {
        Assert::assertArraySubset(['Authorization'], $this->service->headers());
    }
}
