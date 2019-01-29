<?php

namespace App\Form\Type;

use App\Entity\Manufacturer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\ManufacturerRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ManufacturerType extends AbstractType
{
    private $token;
    private $securityChecker;

    public function __construct(AuthorizationCheckerInterface $securityChecker, TokenStorageInterface $token)
    {
        $this->token = $token;
        $this->securityChecker = $securityChecker;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $token = $this->token;
        $resolver->setDefaults([
            'class' => Manufacturer::class,
            'query_builder' => function (ManufacturerRepository $team)  use ($token) {
                return $team->createQueryBuilder('m')
                    ->orderBy('m.nom', 'ASC');
            },
            'choice_label' => 'nom',
            'expanded' => false,
            'multiple' => false,
        ]);
    }

    public function getParent(){
        return EntityType::class;
    }
}
