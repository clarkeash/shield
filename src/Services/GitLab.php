<?php

namespace Clarkeash\Shield\Services;

use Illuminate\Http\Request;

class GitLab extends BaseService
{
    public function verify(Request $request): bool
    {
        return $this->header($request, 'X-Gitlab-Token') == config('shield.services.gitlab.token');
    }

    public function headers(): array
    {
        return ['X-Gitlab-Token'];
    }
}
