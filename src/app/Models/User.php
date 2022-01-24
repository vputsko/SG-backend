<?php

declare(strict_types = 1);

namespace App\Models;

class User
{

    use Traits\TimeStampableTrait;
    
    private int $id;
    private string $name;
    private string $email;
    private string $password;

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
     * Get user Name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set user Name
     *
     * @param  string  $username
     * @return void
     */
    public function setName(string $username): void
    {
        $this->name = $username;
    }

    /**
     * Get user email
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set user email
     *
     * @param  string  $email
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Get user password
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set user password
     *
     * @param  string  $password
     * @return void
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

}

