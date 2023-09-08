<?php

/**
 * User service.
 */

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserService.
 */
class UserService implements UserServiceInterface
{
    /**
     * User repository.
     *
     * @var UserRepository User repository
     */
    private UserRepository $userRepository;

    /**
     * User password encoder.
     *
     * @var UserPasswordHasherInterface Password hasher
     */
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * Security.
     *
     * @var Security security
     */
    private Security $security;

    /**
     * Constructor.
     *
     * @param UserRepository              $userRepository User repository
     * @param UserPasswordHasherInterface $passwordHasher Password hasher
     * @param Security                    $security       Security
     */
    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, Security $security)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->security = $security;
    }

    /**
     * Save user.
     *
     * @param User $user User entity
     */
    public function save(User $user): void
    {
        $this->userRepository->save($user);
    }

    /**
     * Save user password.
     *
     * @param User $user User entity
     */
    public function savePassword(User $user): void
    {
        if ($plainPassword = $user->getPassword()) {
            $user->setPassword($this->hashPassword($user, $plainPassword));
        }

        $this->userRepository->save($user);
    }

    /**
     * Get current user.
     *
     * @return UserInterface User interface
     */
    public function getCurrentUser(): UserInterface
    {
        return $this->security->getUser();
    }

    /**
     * Encode password.
     *
     * @param User   $user          User entity
     * @param string $plainPassword Plain password
     *
     * @return string Encoded password
     */
    private function hashPassword(User $user, string $plainPassword): string
    {
        return $this->passwordHasher->hashPassword(
            $user,
            $plainPassword,
        );
    }
}
