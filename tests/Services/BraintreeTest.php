<?php

namespace Clarkeash\Shield\Test\Services;

use Braintree_Configuration;
use Braintree_WebhookNotification;
use Braintree_WebhookTesting;
use Clarkeash\Shield\Contracts\Service;
use Clarkeash\Shield\Services\Braintree;
use Clarkeash\Shield\Test\TestCase;
use PHPUnit\Framework\Assert;

class BraintreeTest extends TestCase
{
    /**
     * @var Braintree
     */
    protected $service;

    protected function setUp()
    {
        parent::setUp();

        // Configure Braintree
        Braintree_Configuration::environment($this->app['config']['shield.services.braintree.environment']);
        Braintree_Configuration::merchantId($this->app['config']['shield.services.braintree.merchant_id']);
        Braintree_Configuration::publicKey($this->app['config']['shield.services.braintree.public_key']);
        Braintree_Configuration::privateKey($this->app['config']['shield.services.braintree.private_key']);

        $this->service = new Braintree;
    }

    /** @test */
    public function it_is_a_service()
    {
        Assert::assertInstanceOf(Braintree::class, new Braintree);
    }

    /** @test */
    public function it_can_verify_a_valid_request()
    {
        $sampleNotification = Braintree_WebhookTesting::sampleNotification(
            Braintree_WebhookNotification::SUBSCRIPTION_WENT_PAST_DUE,
            'my_id'
        );

        $request = $this->request();
        $request->replace($sampleNotification);

        Assert::assertTrue($this->service->verify($request));
    }

    /** @test */
    public function it_will_not_verify_a_bad_request()
    {
        $this->app['config']['shield.services.braintree.public_key'] = 'invalid-public-key';

        $sampleNotification = Braintree_WebhookTesting::sampleNotification(
            Braintree_WebhookNotification::SUBSCRIPTION_WENT_PAST_DUE,
            'my_id'
        );

        $request = $this->request();
        $request->replace($sampleNotification);

        Assert::assertFalse($this->service->verify($request));
    }
}
