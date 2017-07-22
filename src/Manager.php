<?php

namespace Clarkeash\Shield;

use Clarkeash\Shield\Contracts\Service;
use Clarkeash\Shield\Exceptions\UnknownServiceException;
use Clarkeash\Shield\Services\GitHub;
use Closure;
use Illuminate\Http\Request;

class Manager
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $services;

    /**
     * Manager constructor.
     */
    public function __construct()
    {
        $this->services = collect();

        $this->load();
    }

    /**
     * Registers the enabled services
     */
    protected function load()
    {
        foreach (config('shield.enabled') as $name => $class) {
            $this->register($name, $class);
        }
    }

    /**
     * @param string $service
     *
     * @return bool
     */
    public function has(string $service)
    {
        return $this->services->has($service);
    }

    /**
     * @param string $service
     *
     * @return \Clarkeash\Shield\Contracts\Service | null
     */
    public function get(string $service)
    {
        return $this->services->get($service);
    }

    /**
     * @param string                              $service
     * @param \Clarkeash\Shield\Contracts\Service $instance
     */
    public function put(string $service, Service $instance)
    {
        $this->services->put($service, $instance);
    }

    /**
     * @param string                   $service
     * @param \Illuminate\Http\Request $request
     *
     * @return boolean
     */
    public function passes(string $service, Request $request): bool
    {
        throw_unless($this->has($service), UnknownServiceException::class, 'Service ['.$service.'] is not recognised.');

        return $this->get($service)->verify($request);
    }

    /**
     * @param string $service
     * @param        $instance
     *
     * @throws \Clarkeash\Shield\Exceptions\UnknownServiceException
     */
    public function register(string $service, $instance)
    {
        if (is_string($instance) && class_exists($instance)) {
            $instance = app($instance);
        }

        if ($instance instanceof Closure) {
            $instance = $instance();
        }

        if (is_object($instance) && $instance instanceof Service) {
            return $this->put($service, $instance);
        }

        throw new UnknownServiceException('The service must be an instance of ' . Service::class);
    }
}
