<?php

namespace Clarkeash\Shield\Test\Unit;

use Clarkeash\Shield\Contracts\Service;
use Clarkeash\Shield\Http\Middleware\Shield;
use Clarkeash\Shield\Manager;
use Clarkeash\Shield\Test\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PHPUnit\Framework\Assert;

class ShieldMiddlewareTest extends TestCase
{
    /**
     * @test
     * @expectedException \Clarkeash\Shield\Exceptions\UnknownServiceException
     */
    public function it_throws_exception_if_unknown_service_provided()
    {
        /** @var Shield $middleware */
        $middleware = app(Shield::class);

        $middleware->handle($this->request(), function (){}, 'unknown');
    }
    
    /** @test */
    public function it_responds_with_bad_request_if_check_fails()
    {
        $manager = new Manager;

        $manager->register('custom', new class implements Service {
            public function verify(Request $request): bool
            {
                return false;
            }

            public function headers(): array
            {
                return [];
            }
        });

        $middleware = new Shield($manager);

        /** @var Response $response */
        $response = $middleware->handle($this->request(), function (){}, 'custom');

        Assert::assertInstanceOf(Response::class, $response);
        Assert::assertEquals(400, $response->getStatusCode());
        Assert::assertEquals('Bad Request', $response->getContent());
    }

    /** @test */
    public function it_calls_next_if_successful()
    {
        $manager = new Manager;

        $manager->register('custom', new class implements Service {
            public function verify(Request $request): bool
            {
                return true;
            }

            public function headers(): array
            {
                return [];
            }
        });

        $middleware = new Shield($manager);

        $resp = Response::create('Test', 200);

        $closure =  function () use ($resp) {
            return $resp;
        };

        /** @var Response $response */
        $response = $middleware->handle($this->request(), $closure, 'custom');

        Assert::assertSame($resp, $response);
    }
}
