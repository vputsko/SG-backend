<?php

declare(strict_types = 1);

namespace App\Command;

use App\Messages\SendMoneyMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class SendMoneyCommand extends Command
{

    private QuestionHelper $helper;

    public function __construct(QuestionHelper $helper, MessageBusInterface $bus)
    {
        $this->helper = $helper;
        $this->bus = $bus;

        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $limit = (int) $input->getArgument('limit');
        for ($i = 0; $i < $limit; $i++) {
            $this->bus->dispatch(new SendMoneyMessage(0));
        }
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDefinition([
                new InputArgument('limit', InputArgument::OPTIONAL, 'Specify the number of processed payment.', 1),
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