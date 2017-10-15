<?php

namespace Clarkeash\Shield\Test\Services;

use Clarkeash\Shield\Contracts\Service;
use Clarkeash\Shield\Services\FacebookMessenger;
use Clarkeash\Shield\Test\TestCase;
use PHPUnit\Framework\Assert;

class FacebookMessengerTest extends TestCase
{
    /**
     * @var FacebookMessenger
     */
    protected $service;

    protected function setUp()
    {
        parent::setUp();

        $this->service = new FacebookMessenger;
    }

    /** @test */
    public function it_is_a_service()
    {
        Assert::assertInstanceOf(Service::class, new FacebookMessenger);
    }

    /** @test */
    public function it_can_verify_a_valid_request()
    {
         $secret = 'raNd0mk3y';

        $this->app['config']['shield.services.facebook-messenger.app_secret'] = $secret;

        $content = 'sample content';

        $request = $this->request($content);

        $headers = [
            'X-Hub-Signature' => 'sha1=' . hash_hmac('sha1', $content, $secret)
        ];

        $request->headers->add($headers);

        Assert::assertTrue($this->service->verify($request));
    }

    /** @test */
    public function it_will_not_verify_a_bad_request()
    {
        $this->app['config']['shield.services.github.token'] = 'good';

        $content = 'sample content';

        $request = $this->request($content);

        $headers = [
            'X-Hub-Signature' => 'sha1=' . hash_hmac('sha1', $content, 'bad')
        ];

        $request->headers->add($headers);

        Assert::assertFalse($this->service->verify($request));
    }

    /** @test */
    public function it_has_correct_headers_required()
    {
        Assert::assertArraySubset(['X-Hub-Signature'], $this->service->headers());
    }

}
