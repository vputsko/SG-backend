<?php

namespace Tests\Unit\Kernel;

use App\Kernel\Application;
use Middlewares\Utils\Dispatcher;
use Tests\TestCase;

class ApplicationTest extends TestCase
{

    public function testRun(): void
    {

        $app = new Application();

        $middleware = $this->getMockBuilder(Dispatcher::class)->disableOriginalConstructor()->getMock();
        $middleware->expects($this->once())
            ->method('dispatch');

        $setRequestClosure = function () use ($middleware){
            $this->middleware = $middleware;
        };

        $doSetRequestClosure = $setRequestClosure->bindTo($app, get_class($app));
        $doSetRequestClosure();

        $app->run();
    }

}
