<?php

namespace App\Form;

use App\Entity\NouvelleAdresse;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChoixAdresseType extends AbstractType
{


    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $user = $this->em->getRepository(User::class)->findAll();

        $builder
            ->add('nomAdresse', EntityType::class, [
                'class'=> NouvelleAdresse::class,
                'label' => 'Choisir une autre adresse *',
                'multiple'=>false,
                'expanded'=>false,
                'choice_label' => 'nomAdresse',
                'placeholder' => '  '
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NouvelleAdresse::class,
        ]);
    }
}
