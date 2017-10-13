<?php

namespace Clarkeash\Shield\Services;

use Illuminate\Http\Request;

class Mailgun extends BaseService
{
    public function verify(Request $request): bool
    {
        $tolerance = config('shield.services.mailgun.tolerance', 60 * 5);
        $timestamp = $request->input('timestamp');

        if (
            ! $request->isMethod('POST') ||
            abs(time() - $timestamp) > $tolerance
        ) {
            return false;
        }

        $signature = $this->buildSignature(
            $request->input('timestamp'),
            $request->input('token')
        );

        return $signature === $request->input('signature');
    }

    public function headers(): array
    {
        return [];
    }

    /**
     * Build the signature for verification
     *
     * @param string $timestamp
     * @param string $token
     * @return string
     */
    protected function buildSignature(string $timestamp, string $token)
    {
        return hash_hmac(
            'sha256',
            sprintf('%s%s', $timestamp, $token),
            config('shield.services.mailgun.token')
        );
    }
}