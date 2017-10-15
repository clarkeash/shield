<?php

namespace Clarkeash\Shield\Test\Services;

use Carbon\Carbon;
use Clarkeash\Shield\Contracts\Service;
use Clarkeash\Shield\Services\Mailgun;
use Clarkeash\Shield\Test\TestCase;
use Illuminate\Http\Request;
use PHPUnit\Framework\Assert;

class MailgunTest extends TestCase
{
    /**
     * @var Mailgun
     */
    protected $service;

    protected function setUp()
    {
        parent::setUp();

        $this->service = new Mailgun;
    }

    /** @test */
    public function it_is_a_service()
    {
        Assert::assertInstanceOf(Service::class, new Mailgun);
    }

    /** @test */
    public function it_can_verify_a_valid_request()
    {
        $token = 'raNd0mk3y';
        $this->app['config']['shield.services.mailgun.token'] = $token;

        // Build the signature for the request payload
        $timestamp = Carbon::now()->timestamp;
        $signature = $this->buildSignature($timestamp, $token);

        $request = $this->request(json_encode([
            'timestamp' => $timestamp,
            'token' => $token,
            'signature' => $signature,
        ]));

        $headers = [
            'Content-Type' => 'application/json'
        ];

        $request->headers->add($headers);

        Assert::assertTrue($this->service->verify($request));
    }

    /** @test */
    public function it_will_not_verify_an_old_request()
    {
        $token = 'raNd0mk3y';
        $tolerance = 60;

        $this->app['config']['shield.services.mailgun.token'] = $token;
        $this->app['config']['shield.services.mailgun.tolerance'] = $tolerance;

        // Build the signature for the request payload
        $timestamp = Carbon::now()->subSeconds($tolerance + 1)->timestamp;
        $signature = $this->buildSignature($timestamp, $token);

        $request = $this->request(json_encode([
            'timestamp' => $timestamp,
            'token' => $token,
            'signature' => $signature,
        ]));

        $headers = [
            'Content-Type' => 'application/json'
        ];

        $request->headers->add($headers);

        Assert::assertFalse($this->service->verify($request));
    }

    /** @test */
    public function it_will_not_verify_a_bad_request()
    {
        $token = 'good';
        $this->app['config']['shield.services.mailgun.token'] = $token;

        // Build the signature for the request payload
        $timestamp = Carbon::now()->timestamp;
        $signature = $this->buildSignature($timestamp, $token);

        $request = $this->request(json_encode([
            'timestamp' => $timestamp,
            'token' => 'bad',
            'signature' => $signature,
        ]));

        $headers = [
            'Content-Type' => 'application/json'
        ];

        $request->headers->add($headers);

        Assert::assertFalse($this->service->verify($request));
    }

    /** @test */
    public function it_requires_a_post_request()
    {
        // Set up valid data
        $token = 'raNd0mk3y';
        $this->app['config']['shield.services.mailgun.token'] = $token;

        // Build the signature for the request payload
        $timestamp = Carbon::now()->timestamp;
        $signature = $this->buildSignature($timestamp, $token);

        $requestBody = json_encode([
            'timestamp' => $timestamp,
            'token' => $token,
            'signature' => $signature,
        ]);

        $examples = ['GET', 'PUT', 'PATCH', 'DELETE', 'POST'];

        foreach ($examples as $example) {

            $request = Request::create('http://example.com', $example, [], [], [], [], $requestBody);
            $request->headers->add([
                'Content-Type' => 'application/json'
            ]);

            $assertion = $example === 'POST' ? 'assertTrue' : 'assertFalse';

            Assert::$assertion(
                $this->service->verify($request),
                "Expected $example to $assertion, but it did not."
            );
        }
    }

    /** @test */
    public function it_has_correct_headers_required()
    {
        Assert::assertArraySubset([], $this->service->headers());
    }

    protected function buildSignature($timestamp, $token)
    {
        return hash_hmac(
            'sha256',
            sprintf('%s%s', $timestamp, $token),
            config('shield.services.mailgun.token')
        );
    }
}
