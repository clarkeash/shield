<?php
namespace Clarkeash\Shield\Test\Services;

use Clarkeash\Shield\Contracts\Service;
use Clarkeash\Shield\Services\Trello;
use Clarkeash\Shield\Test\TestCase;
use PHPUnit\Framework\Assert;

class TrelloTest extends TestCase
{
    /**
     * @var Trello
     */
    protected $service;

    protected function setUp()
    {
        parent::setUp();

        $this->service = new Trello;
    }

    /** @test */
    public function it_is_a_service()
    {
        Assert::assertInstanceOf(Service::class, new Trello);
    }

    /** @test */
    public function it_can_verify_a_valid_request()
    {
        $secret = 'sample secret';

        $this->app['config']['shield.services.trello.app_secret'] = $secret;

        $content = 'sample content';

        $request = $this->request($content);

        $signature = hash_hmac('sha1', $content . $request->fullUrl(), $secret, true);

        $headers = [
            'X-Trello-Webhook' => base64_encode($signature)
        ];

        $request->headers->add($headers);

        Assert::assertTrue($this->service->verify($request));
    }

    /** @test */
    public function it_will_not_verify_a_bad_request()
    {
        $secret = 'sample secret';

        $this->app['config']['shield.services.trello.app_secret'] = $secret;

        $content = 'sample content';

        $request = $this->request($content);

        $signature = hash_hmac('sha1', $content . $request->fullUrl(), 'bad', true);

        $headers = [
            'X-Trello-Webhook' => base64_encode($signature)
        ];

        $request->headers->add($headers);

        Assert::assertFalse($this->service->verify($request));
    }

    /** @test */
    public function it_has_correct_headers_required()
    {
        Assert::assertArraySubset(['X-Trello-Webhook'], $this->service->headers());
    }
}