<?php

namespace App\Security\Voter;

use App\Form\UserType;
use App\Security\AppAccess;
use App\Entity\User;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use Symfony\Component\Security\Core\Security;

class UserVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute ,[AppAccess::USER_EDIT, AppAccess::MONPANIER] )){
            return false;
        }

        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }
        if ($this->security->isGranted('ROLE_ADMIN') === true) {
            return true;
        }
        return $subject->getId() === $user->getId();

    }
}