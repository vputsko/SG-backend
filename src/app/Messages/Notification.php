<?php

declare(strict_types = 1);

namespace App\Messages;

class Notification
{
    
    private string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }
    
}