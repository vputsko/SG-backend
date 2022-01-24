<?php

declare(strict_types = 1);

namespace App\Command;

use Symfony\Component\Messenger\Command\ConsumeMessagesCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SymfonyConsumeMessagesCommand extends ConsumeMessagesCommand
{

    public function execute(InputInterface $input, OutputInterface $output): int
    {

        $input->setOption('limit', (int) $input->getOption('limit'));
        $input->setOption('failure-limit', (int) $input->getOption('failure-limit'));
        $input->setOption('time-limit', (int) $input->getOption('time-limit'));
        
        return parent::execute($input, $output);
    }

}