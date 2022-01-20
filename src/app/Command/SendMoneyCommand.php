<?php

declare(strict_types = 1);

namespace App\Command;

use App\Services\BankApiInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendMoneyCommand extends Command
{

    private QuestionHelper $helper;
    private BankApiInterface $bankApi;

    public function __construct(QuestionHelper $helper, BankApiInterface $bankApi)
    {
        $this->helper = $helper;
        $this->bankApi = $bankApi;

        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDefinition([
                new InputArgument('limit', InputArgument::REQUIRED, 'Specify the number of processed payment.'),
                ])
            ->setDescription('Process payments')
            ->setHelp(<<<'EOF'
The <info>%command.name%</info> command processes payments and dispatches them.

    <info>php %command.full_name% limit<receiver-name></info>

Use the argument limit to specify the number of processed payment.
EOF
            );
    }

}