<?php

namespace App\Form;

use App\Entity\NouvelleAdresse;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class NouvelleAdresseType extends AbstractType
{
    private $securityChecker;
    private $token;

    public function __construct(AuthorizationCheckerInterface $securityChecker, TokenStorageInterface $token)
    {
        $this->securityChecker = $securityChecker;
        $this->token = $token;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('adresse')
            ->add('code_postal')
            ->add('ville')
            ->add('pays')
            ->add('telephone')
            ->add('information', TextareaType::class, [
                'label' => 'Informations complémentaires'
            ])
            ->add('nomAdresse',TextType::class, [
                'label' => 'Donnez un titre à cette adresse *',
                'required' => true,
                'attr' => [
                    'placeholder' => 'ex : MonAdresse2'
                ]
            ])
            ->add('user', null , [
            ]);

        $builder->addEventListener(
         FormEvents::PRE_SET_DATA,
         array($this, 'preSetData')
     );
    }

    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $nouvelle_adresse = $event->getData();

        $nouvelle_adresse->setUser($this->token->getToken()->getUser());
        $form->remove('user');

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NouvelleAdresse::class,
        ]);
    }
}
