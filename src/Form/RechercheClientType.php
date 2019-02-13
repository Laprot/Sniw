<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechercheClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rechercheClient', TextType::class,  [
                'label'=> false,
                'method' => 'GET',
                'attr' => ['class' => 'form-control' , 'type' => 'search' , 'aria-bel'=> 'Rechercher'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
