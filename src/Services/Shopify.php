<?php

namespace Clarkeash\Shield\Services;

use Illuminate\Http\Request;

class Shopify extends BaseService
{
    public function verify(Request $request): bool
    {

        $generated = base64_encode(
            hash_hmac(
                'sha256',
                $request->getContent(),
                config("shield.services.shopify.token"),
                true
            )
        );

        return hash_equals(
            $generated,
            $this->header($request, 'X-Shopify-Hmac-SHA256')
        );
    }

    public function headers(): array
    {
        return ['X-Shopify-Hmac-SHA256'];
    }
}
