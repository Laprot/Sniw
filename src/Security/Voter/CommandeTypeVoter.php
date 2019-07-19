<?php

namespace App\Security\Voter;

use App\Entity\CommandeTypeProduits;
use App\Form\UserType;
use App\Security\AppAccess;
use App\Entity\User;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use Symfony\Component\Security\Core\Security;

class CommandeTypeVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute ,[AppAccess::COMMANDETYPE_EDIT] )){
            return false;
        }

        if (!$subject instanceof CommandeTypeProduits) {
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

        foreach($subject->getUsers() as $sub) {
            $idUser = $sub->getId();

            if ($idUser == $user->getId()) {
                return $idUser == $user->getId();
            }

        }


    }
}