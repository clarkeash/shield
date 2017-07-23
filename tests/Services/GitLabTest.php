<?php

namespace Clarkeash\Shield\Test\Services;

use Clarkeash\Shield\Contracts\Service;
use Clarkeash\Shield\Services\GitLab;
use Clarkeash\Shield\Test\TestCase;
use PHPUnit\Framework\Assert;

class GitLabTest extends TestCase
{
    /**
     * @var GitLab
     */
    protected $service;

    protected function setUp()
    {
        parent::setUp();

        $this->service = new GitLab;
    }

    /** @test */
    public function it_is_a_service()
    {
        Assert::assertInstanceOf(Service::class, new GitLab);
    }

    /** @test */
    public function it_can_verify_a_valid_request()
    {
        $token = 'raNd0mk3y';

        $this->app['config']['shield.services.gitlab.token'] = $token;

        $request = $this->request();

        $headers = [
            'X-Gitlab-Token' => $token
        ];

        $request->headers->add($headers);

        Assert::assertTrue($this->service->verify($request));
    }

    /** @test */
    public function it_will_not_verify_a_bad_request()
    {
        $this->app['config']['shield.services.gitlab.token'] = 'good';

        $request = $this->request();

        $headers = [
            'X-Gitlab-Token' => 'bad'
        ];

        $request->headers->add($headers);

        Assert::assertFalse($this->service->verify($request));
    }

    /** @test */
    public function it_has_correct_headers_required()
    {
        Assert::assertArraySubset(['X-Gitlab-Token'], $this->service->headers());
    }
}
