<?php

namespace Clarkeash\Shield\Services;

use Braintree_Configuration;
use Braintree_Exception_InvalidSignature;
use Braintree_WebhookNotification;
use Illuminate\Http\Request;

class Braintree extends BaseService
{
    public function verify(Request $request): bool
    {
        $this->configure();

        try {
            Braintree_WebhookNotification::parse($request->bt_signature, $request->bt_payload);
        } catch (Braintree_Exception_InvalidSignature $exception) {
            return false;
        }

        return true;
    }

    protected function configure()
    {
        Braintree_Configuration::environment(config('shield.services.braintree.environment'));
        Braintree_Configuration::merchantId(config('shield.services.braintree.merchant_id'));
        Braintree_Configuration::publicKey(config('shield.services.braintree.public_key'));
        Braintree_Configuration::privateKey(config('shield.services.braintree.private_key'));
    }

    public function headers(): array
    {
        return [];
    }
}
