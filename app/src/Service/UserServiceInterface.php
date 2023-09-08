<?php

/**
 * User service interface.
 */

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Interface UserServiceInterface.
 */
interface UserServiceInterface
{
    /**
     * Save user.
     *
     * @param User $user User entity
     */
    public function save(User $user): void;

    /**
     * Save user password.
     *
     * @param User $user User entity
     */
    public function savePassword(User $user): void;

    /**
     * Get current user.
     *
     * @return UserInterface User Interface
     */
    public function getCurrentUser(): UserInterface;
}
