<?php

namespace Clarkeash\Shield\Test\Services;

use Clarkeash\Shield\Contracts\Service;
use Clarkeash\Shield\Services\TravisCI;
use Clarkeash\Shield\Test\TestCase;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\Assert;

class TravisCITest extends TestCase
{
    /**
     * @var TravisCI
     */
    protected $service;

    /**
     * @var vfsStreamDirectory
     */
    protected $root;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $publicKey;

    /**
     * @var string
     */
    protected $payload;

    /**
     * @var string
     */
    protected $signature;

    protected function setUp()
    {
        parent::setUp();

        $this->root = vfsStream::setup('tmp');

        $this->url = vfsStream::url('tmp/config.json');

        $this->service = new TravisCI($this->url);

        $this->setUpSignedPayload();
    }

    /** @test */
    public function it_is_a_service()
    {
        Assert::assertInstanceOf(Service::class, $this->service);
    }

    /** @test */
    public function it_can_verify_a_valid_request()
    {
        $request = $this->request();

        $headers = [
            'Signature' => base64_encode($this->signature)
        ];

        $request->replace([
            'payload' => $this->payload
        ]);

        $request->headers->add($headers);

        Assert::assertTrue($this->service->verify($request));
    }

    /** @test */
    public function it_will_not_verify_a_bad_request()
    {
        $request = $this->request();

        $headers = [
            'Signature' => base64_encode($this->signature)
        ];

        $request->replace([
            'payload' => $this->payload . ' bad value'
        ]);

        $request->headers->add($headers);

        Assert::assertFalse($this->service->verify($request));
    }

    /**
     * @test
     * @expectedException \UnexpectedValueException
     */
    public function it_will_not_verify_a_bad_configuration()
    {
        $request = $this->request();

        $headers = [
            'Signature' => base64_encode($this->signature)
        ];

        $request->replace([
            'payload' => $this->payload . ' bad value'
        ]);

        file_put_contents($this->url, '}');

        $request->headers->add($headers);

        $this->service->verify($request);
    }

    /** @test */
    public function it_has_correct_headers_required()
    {
        Assert::assertArraySubset(['Signature'], $this->service->headers());
    }

    protected function setUpSignedPayload()
    {
        $signature = '';
        $payload = 'sample content';

        $privateKey = openssl_pkey_new([
            'private_key_bits' => 1024,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        $details = openssl_pkey_get_details($privateKey);

        openssl_sign($payload, $signature, $privateKey, 'sha1WithRSAEncryption');

        $this->payload = $payload;

        $this->signature = $signature;

        $this->publicKey = $details['key'];

        file_put_contents($this->url, json_encode([
            'config' => [
                'notifications' => [
                    'webhook' => [
                        'public_key' => $this->publicKey
                    ]
                ]
            ]
        ]));
    }
}
