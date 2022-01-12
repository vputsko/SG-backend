<?php

declare(strict_types=1);

namespace App\Services;

use Bernard\Message;

class NewsletterMessageHandler
{

    public function sendNewsletter(Message $message): void
    {
        var_dump($message);
    }

}
