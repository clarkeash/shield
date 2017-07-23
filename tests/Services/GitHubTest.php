<?php

namespace Clarkeash\Shield\Test\Services;

use Clarkeash\Shield\Contracts\Service;
use Clarkeash\Shield\Services\GitHub;
use Clarkeash\Shield\Test\TestCase;
use PHPUnit\Framework\Assert;

class GitHubTest extends TestCase
{
    /**
     * @var GitHub
     */
    protected $service;

    protected function setUp()
    {
        parent::setUp();

        $this->service = new GitHub;
    }

    /** @test */
    public function it_is_a_service()
    {
        Assert::assertInstanceOf(Service::class, new GitHub);
    }

    /** @test */
    public function it_can_verify_a_valid_request()
    {
        $token = 'raNd0mk3y';

        $this->app['config']['shield.services.github.token'] = $token;

        $content = 'sample content';

        $request = $this->request($content);

        $headers = [
            'X-Hub-Signature' => 'sha1=' . hash_hmac('sha1', $content, $token)
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
