<?php

namespace Clarkeash\Shield\Test\Unit;

use Clarkeash\Shield\Contracts\Service;
use Clarkeash\Shield\Manager;
use Clarkeash\Shield\Test\TestCase;
use Illuminate\Http\Request;
use PHPUnit\Framework\Assert;

class ManagerTest extends TestCase
{
    /**
     * @var Manager
     */
    protected $manager;

    public function setUp()
    {
        parent::setUp();

        $this->manager = new Manager;
    }

    /** @test */
    public function it_knows_if_a_service_is_registered()
    {
        Assert::assertTrue($this->manager->has('github'));
        Assert::assertFalse($this->manager->has('somethingelse'));
    }

    /** @test */
    public function a_service_can_be_registered_as_a_string()
    {
        $this->manager->register('example', Example::class);

        Assert::assertTrue($this->manager->has('example'));
        Assert::assertTrue($this->manager->passes('example', $this->request()));
    }

    /** @test */
    public function a_service_can_be_registered_as_an_object()
    {
        $this->manager->register('example', new Example);

        Assert::assertTrue($this->manager->has('example'));
        Assert::assertTrue($this->manager->passes('example', $this->request()));
    }

    /** @test */
    public function a_service_can_be_registered_as_a_closure()
    {
        $this->manager->register('example', function(){
            return new Example;
        });

        Assert::assertTrue($this->manager->has('example'));
        Assert::assertTrue($this->manager->passes('example', $this->request()));
    }

    /**
     * @test
     * @expectedException \Clarkeash\Shield\Exceptions\UnknownServiceException
     */
    public function it_throws_exception_if_non_service_class_is_registered()
    {
        $this->manager->register('example', new class{});
    }

    /** @test */
    public function it_fails_if_expected_header_is_not_there()
    {
        $this->manager->register('example', new class implements Service {

            public function verify(Request $request): bool
            {
                return true;
            }

            public function headers(): array
            {
                return ['X-Custom-Header'];
            }
        });

        Assert::assertFalse($this->manager->passes('example', $this->request()));
    }

    /** @test */
    public function it_passes_if_expected_header_is_there()
    {
        $this->manager->register('example', new class implements Service {

            public function verify(Request $request): bool
            {
                return true;
            }

            public function headers(): array
            {
                return ['X-Custom-Header'];
            }
        });

        $request = $this->request();
        $request->headers->add(['X-Custom-Header' => 'custom data']);

        Assert::assertTrue($this->manager->passes('example', $request));
    }
}

class Example implements Service {
    public function verify(Request $request): bool
    {
        return true;
    }

    public function headers(): array
    {
        return [];
    }
}
