<?php

namespace Tests\Feature\Command;

use App\Command\SymfonyConsumeMessagesCommand;
use DI\ContainerBuilder;
use Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class SymfonyConsumeMessagesCommandTest extends TestCase
{

    public function testExecute(): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions(__DIR__."/../../../config/di.php");
        $container = $builder->build();

        $command = $container->get(SymfonyConsumeMessagesCommand::class);
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'receivers' => ['async'],
            '--limit' => 1,
            '--failure-limit' => 0,
            '--memory-limit' => 0,
            '--time-limit' => 1,
        ]);

        $commandTester->assertCommandIsSuccessful();
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('[OK]', $output);
    }
    
}
