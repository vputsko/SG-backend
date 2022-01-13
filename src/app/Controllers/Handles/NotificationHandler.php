<?php

declare(strict_types = 1);

namespace App\Controllers\Handles;

use App\Messages\Notification;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class NotificationHandler implements MessageHandlerInterface
{

    /**
     * @param  Notification  $message
     * @return array
     */
    public function __invoke(Notification $message): array
    {
        var_dump("NotificationHandler");
        var_dump($message);

        return [
            'success' => true,
            'message' => $message,
        ];
    }
    
}