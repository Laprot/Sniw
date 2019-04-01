<?php

namespace App\Security\Voter;

use App\Entity\Commande;
use App\Form\UserType;
use App\Security\AppAccess;
use App\Entity\User;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use Symfony\Component\Security\Core\Security;

class CommandeVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute ,[AppAccess::COMMANDE_EDIT] )){
            return false;
        }

        if (!$subject instanceof Commande) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User ) {
            return false;
        }
        if ($this->security->isGranted('ROLE_ADMIN') === true) {
            return true;
        }
        return $subject->getUtilisateur()->getId() == $user->getId();

    }
}