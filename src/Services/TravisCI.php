<?php

namespace Clarkeash\Shield\Services;

use Illuminate\Http\Request;

class TravisCI extends BaseService
{
    private $configSource;

    /**
     * Travis constructor.
     * @param string $configSource
     */
    public function __construct($configSource = 'https://api.travis-ci.org/config')
    {
        $this->configSource = $configSource;
    }

    public function verify(Request $request): bool
    {
        $signature = $request->header('Signature');

        $payload = $request->input('payload');

        $publicKey = $this->getPublicKey();

        return openssl_verify($payload, base64_decode($signature), $publicKey) === 1;
    }

    protected function getPublicKey()
    {
        $config = file_get_contents($this->configSource);

        if (!$config) {
            throw new \UnexpectedValueException("Could not fetch the content from {$this->configSource}.");
        }

        $travisConfig = json_decode($config);

        if (!$travisConfig) {
            throw new \UnexpectedValueException("Configuration fetched from {$this->configSource} is not valid JSON.");
        }

        $publicKey = $travisConfig->config->notifications->webhook->public_key;

        return $publicKey;
    }

    public function headers(): array
    {
        return ['Signature'];
    }
}
