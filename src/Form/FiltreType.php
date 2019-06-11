<?php

namespace App\Form;

use App\Entity\Filtre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isBelleFrance', CheckboxType::class, [
                'label' => 'PRODUITS BELLE FRANCE',
                'required' => false
            ])
            ->add('isBio', CheckboxType::class, [
                'label' => 'PRODUITS BIO',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Filtre::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }
}
