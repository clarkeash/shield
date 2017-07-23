<?php

namespace Clarkeash\Shield\Test\Services;

use Clarkeash\Shield\Contracts\Service;
use Clarkeash\Shield\Services\Stripe;
use Clarkeash\Shield\Test\TestCase;
use PHPUnit\Framework\Assert;

class StripeTest extends TestCase
{
    /**
     * @var Stripe
     */
    protected $service;

    protected function setUp()
    {
        parent::setUp();

        $this->service = new Stripe;
    }

    /** @test */
    public function it_is_a_service()
    {
        Assert::assertInstanceOf(Service::class, new Stripe);
    }

    /** @test */
    public function it_can_verify_a_valid_request()
    {
        $token = 'raNd0mk3y';

        $this->app['config']['shield.services.stripe.token'] = $token;

        $content = 'sample content';

        $request = $this->request($content);

        $time = time();

        $signature = $time . '.' . $content;

        $headers = [
            'Stripe-Signature' => 't=' . $time . ',v1=' . hash_hmac('sha256', $signature, $token)
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

        $time = time();

        $signature = $time . '.' . $content;

        $headers = [
            'Stripe-Signature' => 't=' . $time . ',v1=' . hash_hmac('sha256', $signature, 'bad')
        ];

        $request->headers->add($headers);

        Assert::assertFalse($this->service->verify($request));
    }

    /** @test */
    public function it_will_fail_if_timestamp_is_over_tolerance()
    {
        $token = 'raNd0mk3y';

        $this->app['config']['shield.services.stripe.token'] = $token;
        $this->app['config']['shield.services.stripe.tolerance'] = 60 * 5;

        $content = 'sample content';

        $request = $this->request($content);

        $time = time() - 61 * 5; // 5 seconds over

        $signature = $time . '.' . $content;

        $headers = [
            'Stripe-Signature' => 't=' . $time . ',v1=' . hash_hmac('sha256', $signature, $token)
        ];

        $request->headers->add($headers);

        Assert::assertFalse($this->service->verify($request));
    }

    /** @test */
    public function it_has_correct_headers_required()
    {
        Assert::assertArraySubset(['Stripe-Signature'], $this->service->headers());
    }
}
