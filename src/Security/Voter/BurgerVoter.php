<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class BurgerVoter extends Voter
{
    public const EDIT = 'BURGER_EDIT';
    public const CREATE = 'BURGER_CREATE';

    private $security = null;

    public function __construct(Security $security){
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::CREATE])
            && $subject instanceof \App\Entity\Burger;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:

                // logic to determine if the user can EDIT
                // return true or false
                break;
            case self::CREATE:
                if($this->security->isGranted("Role::GESTIONNAIRE")) return true;
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        return false;
    }
}
