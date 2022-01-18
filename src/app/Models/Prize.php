<?php

declare(strict_types = 1);

namespace App\Models;

class Prize
{
    
    use Traits\TimeStampableTrait;

    private int $id;
    private string $title;

    /**
     * Get user Id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get prize title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set prize title
     *
     * @param  string $title
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
    
}