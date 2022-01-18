<?php

declare(strict_types = 1);

namespace App\Models\Traits;

use DateTime;
use DateTimeInterface;
use Exception;

trait TimeStampableTrait
{

    protected DateTimeInterface $createdAt;
    protected DateTimeInterface $updatedAt;

    /**
     * @return DateTimeInterface|null
     * @throws Exception
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt ?? new DateTime();
    }

    /**
     * @param DateTimeInterface $createdAt
     * @return $this
     */
    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt ?? new DateTime();
    }

    /**
     * @param DateTimeInterface $updatedAt
     * @return $this
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function prePersist(): void
    {
        $this->setCreatedAt(new DateTime());

    }

    public function preUpdate(): void
    {
        $this->setUpdatedAt(new DateTime());
    }

}

