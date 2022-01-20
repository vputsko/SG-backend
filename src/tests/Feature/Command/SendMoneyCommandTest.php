<?php

namespace Tests\Feature\Command;

use App\Command\SendMoneyCommand;
use DI\ContainerBuilder;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\TestCase;

class SendMoneyCommandTest extends TestCase
{

    public function testExecute(): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions(__DIR__."/../../../config/di.php");
        $container = $builder->build();

        $command = $container->get(SendMoneyCommand::class);
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'limit' => 0,
        ]);

        $commandTester->assertCommandIsSuccessful();
    }

}
