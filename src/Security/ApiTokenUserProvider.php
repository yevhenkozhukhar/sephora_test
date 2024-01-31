<?php

namespace App\Security;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface as TUser;
use Symfony\Component\Security\Core\User\UserProviderInterface;

readonly class ApiTokenUserProvider implements UserProviderInterface
{
    public function __construct(private mixed $apiSecretConfigs)
    {
    }

    public function refreshUser(TUser $user): TUser
    {
        if (!$user instanceof ApiTokenUser) {
            throw new UnsupportedUserException();
        }

        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return $class === ApiTokenUser::class;
    }

    public function loadUserByIdentifier(string $identifier): TUser
    {
        $result = array_search($identifier, array_column($this->apiSecretConfigs, 'api_token'), true);

        if ($result === false) {
            throw new UserNotFoundException();
        }
        $user = $this->apiSecretConfigs[$result];

        return new ApiTokenUser($user['username'], $user['api_token'], $user['roles']);
    }
}
