<?php

namespace Clarkeash\Shield\Services;

use Clarkeash\Shield\Contracts\Service;
use Illuminate\Http\Request;

class Stripe implements Service
{
    public function verify(Request $request): bool
    {
        $processed = $this->process($request->header('Stripe-Signature'));

        $tolerance = config('shield.services.stripe.tolerance', 60 * 5);

        if(($tolerance > 0) && ((time() - $processed['t']) > $tolerance)) {
            return false;
        }

        $payload = $processed['t'] . '.' . $request->getContent();
        $generated = hash_hmac('sha256', $payload, config('shield.services.stripe.token'));

        return hash_equals($generated, $processed['v1']);
    }

    protected function process(string $header)
    {
        $sections = explode(',', $header);

        $data = [];

        foreach ($sections as $section) {
            $parts = explode('=', $section);
            $data[$parts[0]] = $parts[1];
        }

        return $data;
    }

    public function headers(): array
    {
        return ['Stripe-Signature'];
    }
}
