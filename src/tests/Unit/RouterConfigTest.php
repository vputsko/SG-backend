<?php

declare(strict_types = 1);

namespace Tests\Unit;

use App\Controllers\LoginController;
use App\Controllers\PrizeController;
use App\Controllers\UserController;
use App\Kernel\Application;
use Tests\TestCase;

class RouterConfigTest extends TestCase
{

   public function testRouterConfig(): void
   {
       $container = Application::buildContainer();

       $routerConfig = require __DIR__.'/../../config/routers.php';
       self::assertIsArray($routerConfig, 'Wrong router rule type.');


       foreach ($routerConfig as $routerRule) {
           self::assertCount(3, $routerRule);
           $routerInvoker = $routerRule[2];

           if (is_string($routerInvoker)) {
               $routerInvoker = $container->get($routerInvoker);
           }

           if (is_array($routerInvoker) && count($routerInvoker) === 2 && is_string($routerInvoker[0])) {
               $routerInvoker[0] = $container->get($routerInvoker[0]);
           }

           $this->assertTrue(
               is_callable($routerInvoker),
               sprintf("Invalid router's handler for %s", $routerRule[1])
           );
       }
   }

}