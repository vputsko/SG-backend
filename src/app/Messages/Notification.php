<?php

declare(strict_types = 1);

namespace App\Messages;

use App\Models\User;

class Notification
{
    
    private array $content;

    public function __construct(array $content)
    {
        $this->content = $content;
    }

    public function getContent(): array
    {
        return $this->content;
    }
    
}