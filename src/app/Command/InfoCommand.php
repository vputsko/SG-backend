<?php

declare(strict_types = 1);

namespace App\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

class InfoCommand
{

    public function __invoke(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Usage: get_user [user_id] - get user by user_id');
    }
    
}

