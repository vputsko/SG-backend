<?php

declare(strict_types = 1);

namespace App\Command;

use Symfony\Component\Messenger\Command\ConsumeMessagesCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SymfonyConsumeMessagesCommand extends ConsumeMessagesCommand
{

    public function __invoke(InputInterface $input, OutputInterface $output): void
    {

        $input->setOption('limit', (int) $input->getOption('limit'));
        $input->setOption('failure-limit', (int) $input->getOption('failure-limit'));
        $input->setOption('time-limit', (int) $input->getOption('time-limit'));

        $this->execute($input, $output);
    }

}