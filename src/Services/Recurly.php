<?php

namespace Clarkeash\Shield\Services;

use Illuminate\Http\Request;

class Recurly extends BaseService
{
    public function verify(Request $request): bool
    {
        $processed = $this->process($this->header($request, 'Authorization'));

        $username = config('shield.services.recurly.username');
        $password = config('shield.services.recurly.password');

        return ($username===$processed[0] && $password===$processed[1]);
    }

    protected function process(string $header): array
    {
        list($token_type, $payload) = explode(" ", $header, 2);
      
        $data = array();
        if (strcasecmp($token_type, "Bearer") == 0) {
            $section = base64_decode($payload);
            $data = explode(':', $section);
        }

        return $data;
    }

    public function headers(): array
    {
        return ['Authorization'];
    }
}
