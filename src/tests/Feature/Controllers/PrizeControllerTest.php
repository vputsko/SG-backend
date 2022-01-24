<?php

declare(strict_types = 1);

namespace Tests\Feature\Controllers;

use App\Controllers\PrizeController;
use App\Models\Prize;
use App\Repositories\PrizeRepositoryInterface;
use Doctrine\ORM\EntityNotFoundException;
use Tests\RefreshesDatabase;
use Tests\TestCase;

require_once __DIR__.'/../../../app/helpers.php';

class PrizeControllerTest extends TestCase
{

    use RefreshesDatabase;

    /**
     * @var PrizeRepositoryInterface
     */
    private $prizeRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->prizeRepository = $this->em->getRepository(Prize::class);
    }

    public function testShowRandomPrize()
    {
        $this->createDatabaseSchema();

        $prizeFirst = new Prize();
        $prizeFirst->setTitle('Money');
        $prizeFirst->setMaxAmount(100);
        $this->em->persist($prizeFirst);
        $prizeSecond = new Prize();
        $prizeSecond->setTitle('Goods');
        $prizeSecond->setMaxAmount(100);
        $this->em->persist($prizeSecond);

        $this->em->flush();

        $prizeController = new PrizeController($this->prizeRepository);

        $response = $prizeController->showRandomPrize();

        self::assertSame(200, $response->getStatusCode());

        $responseArray = json_decode($response->getBody()->getContents(),true);
        self::assertArrayHasKey('id', $responseArray);
        self::assertArrayHasKey('title', $responseArray);
        self::assertArrayHasKey('amount', $responseArray);
        self::assertThat(
            $responseArray['title'],
            $this->logicalOr(
                $this->equalTo('Money'),
                $this->equalTo('Goods')
            )
        );
        self::assertLessThanOrEqual(100, $responseArray['amount']);
        self::assertGreaterThanOrEqual(0, $responseArray['amount']);
    }

    public function testShowRandomPrizeNotFound()
    {
        $this->createDatabaseSchema();
        $prizeController = new PrizeController($this->prizeRepository);

        $this->expectException(EntityNotFoundException::class);
        $prizeController->showRandomPrize();
    }

}