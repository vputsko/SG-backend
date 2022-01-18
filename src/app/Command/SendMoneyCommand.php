<?php

declare(strict_types = 1);

namespace App\Command;

use App\Services\BankApiInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendMoneyCommand
{

    private QuestionHelper $helper;
    private BankApiInterface $bankApi;

    public function __construct(QuestionHelper $helper, BankApiInterface $bankApi)
    {
        $this->helper = $helper;
        $this->bankApi = $bankApi;
    }

    public function __invoke(InputInterface $input, OutputInterface $output): array
    {
        return [];
    }

}