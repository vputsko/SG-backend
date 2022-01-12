<?php

declare(strict_types=1);

namespace App\Command;

use Bernard\Command\ConsumeCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MainConsumeCommand extends ConsumeCommand
{

    public function __invoke(InputInterface $input, OutputInterface $output): void
    {
        $this->execute($input, $output);
    }

}