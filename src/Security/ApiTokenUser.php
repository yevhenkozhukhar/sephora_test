<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;

readonly class ApiTokenUser implements UserInterface
{
    private readonly string $username;

    public function __construct(
        string $username,
        private string $token,
        private array $roles
    ) {
        $this->username = $username;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->token;
    }
}