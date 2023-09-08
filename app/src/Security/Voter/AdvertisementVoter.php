<?php

/**
 * Advertisement voter.
 */

namespace App\Security\Voter;

use App\Entity\Advertisement;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AdvertisementVoter.
 */
class AdvertisementVoter extends Voter
{
    /**
     * Edit permission.
     *
     * @const string
     */
    public const EDIT = 'EDIT';

    /**
     * View permission.
     *
     * @const string
     */
    public const VIEW = 'VIEW';

    /**
     * Delete permission.
     *
     * @const string
     */
    public const DELETE = 'DELETE';

    /**
     * Accept permission.
     *
     * @const string
     */
    public const ACCEPT = 'ACCEPT';

    /**
     * Security helper.
     */
    private Security $security;

    /**
     * OrderVoter constructor.
     *
     * @param Security $security Security helper
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed  $subject   The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool Result
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE, self::ACCEPT])
            && $subject instanceof Advertisement;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string         $attribute Permission name
     * @param mixed          $subject   Object
     * @param TokenInterface $token     Security token
     *
     * @return bool Vote result
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($subject, $user);
            case self::VIEW:
                return $this->canView($subject, $user);
            case self::DELETE:
                return $this->canDelete($user);
            case self::ACCEPT:
                return $this->canAccept($user);
        }

        return false;
    }

    /**
     * Checks if user can edit advertisement.
     *
     * @param Advertisement      $advertisement Advertisement entity
     * @param UserInterface|null $user          User
     *
     * @return bool Result
     */
    private function canEdit(Advertisement $advertisement, UserInterface $user = null): bool
    {
        return true === $advertisement->isIsActive() && $this->security->isGranted('ROLE_ADMIN');
    }

    /**
     * Checks if user can view advertisement.
     *
     * @param Advertisement      $advertisement Advertisement entity
     * @param UserInterface|null $user          User
     *
     * @return bool Result
     */
    private function canView(Advertisement $advertisement, UserInterface $user = null): bool
    {
        return (true === $advertisement->isIsActive()) || (false === $advertisement->isIsActive() && $this->security->isGranted('ROLE_ADMIN'));
    }

    /**
     * Checks if user can delete advertisement.
     *
     * @param UserInterface|null $user User
     *
     * @return bool Result
     */
    private function canDelete(UserInterface $user = null): bool
    {
        return $this->security->isGranted('ROLE_ADMIN');
    }

    /**
     * Checks if user can create advertisement.
     *
     * @param UserInterface|null $user User
     *
     * @return bool Result
     */
    private function canAccept(UserInterface $user = null): bool
    {
        return $this->security->isGranted('ROLE_ADMIN');
    }
}
