<?php

namespace Clarkeash\Shield\Http\Middleware;

use Clarkeash\Shield\Manager;
use Closure;
use Illuminate\Http\Response;

class Shield
{
    /**
     * @var \Clarkeash\Shield\Manager
     */
    protected $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param string                    $service
     *
     * @return mixed
     */
    public function handle($request, Closure $next, string $service)
    {
        if($this->manager->passes($service, $request)) {
            return $next($request);
        }

        return Response::create(Response::$statusTexts[Response::HTTP_BAD_REQUEST], Response::HTTP_BAD_REQUEST);
    }
}
