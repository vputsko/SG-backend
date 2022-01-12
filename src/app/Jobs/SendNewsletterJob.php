<?php

declare(strict_types = 1);

namespace App\Jobs;

use App\Support\EnvelopeEvent;
use Bernard\Envelope;
use Bernard\Message;
use Bernard\Message\DefaultMessage;
use Bernard\QueueFactory;
use Symfony\Component\EventDispatcher\EventDispatcher;

class SendNewsletterJob
{

    protected ?int $userId = null;

    protected $name = 'SendNewsletter';

    protected QueueFactory $queues;

    protected EventDispatcher $dispatcher;

    /**
     * @param string $name
     * @param array  $parameters
     */
    public function __construct(QueueFactory $queues, EventDispatcher $dispatcher) //$name, array $parameters = array()
    {
        /*if (isset($parameters['user_id'])) {
            $this->userId = $parameters['user_id'];
        }*/

        //$this->name = preg_replace('/(^([0-9]+))|([^[:alnum:]\-_+])/i', '', 'SendNewsletter');

        $this->queues = $queues;
        $this->dispatcher = $dispatcher;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function dispatch(array $parameters = array()): void
    {

        $message = new DefaultMessage($this->name, $parameters);

        $queue = $this->queues->create('send-newsletter');
        $queue->enqueue($envelope = new Envelope($message));

        $this->dispatcher->dispatch(new EnvelopeEvent($envelope, $queue), 'bernard.produce');
    }
    
    public function sendNewsletter(Message $message): void
    {
        var_dump($message);
    }

}