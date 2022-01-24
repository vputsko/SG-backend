<?php

namespace Tests\Unit\Controllers;

use App\Controllers\PrizeController;
use App\Repositories\PrizeRepositoryInterface;
use Tests\TestCase;

require_once __DIR__.'/../../../app/helpers.php';

class PrizeControllerTest extends TestCase
{

    public function testShowRandomPrize()
    {
        $prizeRepository = $this->createMock(PrizeRepositoryInterface::class);

        $wilReturn = array(
            "id" => 1,
            "title" => "Money",
            "amount" => 6
        );

        $prizeRepository->expects($this->once())
            ->method('getRandomPrize')
            ->willReturn($wilReturn);

        $userController = new PrizeController($prizeRepository);
        $response = $userController->showRandomPrize();
        self::assertSame($wilReturn, json_decode($response->getBody()->getContents(),true));
    }
}
