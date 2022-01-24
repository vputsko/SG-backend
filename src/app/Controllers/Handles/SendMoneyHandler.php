<?php

declare(strict_types = 1);

namespace App\Controllers\Handles;

use App\Messages\SendMoneyMessage;
use App\Repositories\PaymentsRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Services\BankApiFactory;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;

class SendMoneyHandler implements MessageHandlerInterface
{

    private PaymentsRepositoryInterface $repository;
    private bankApiFactory $bankApiFactory;

    protected MailerInterface $mailer;

    public function __construct(PaymentsRepositoryInterface $repository, BankApiFactory $bankApiFactory)
    {
        $this->repository = $repository;
        $this->bankApiFactory = $bankApiFactory;
    }

    /**
     * Send payment by id or next payment if id=0
     *
     * @param  SendMoneyMessage  $message
     * @return void
     */
    public function __invokes(SendMoneyMessage $message): void
    {
        $paymentId = $message->getPaymentId();

        if (0 != $paymentId) {

            $payment = $this->repository->getPayment($paymentId);
        } else {
            $paymentIds = $this->repository->getAwaitingPayments(1);
            if (! count($paymentIds)) {
                return;
            }
            $payment = $this->repository->getPayment($paymentIds[0]);
        }
        $bankApi = $this->bankApiFactory->getBankApi($payment['bank_api']);
        $bankApi->sendMoney([
            'recipient' => $payment['user'],
            'amount' => $payment['amount'],
        ]);
    }
    
}