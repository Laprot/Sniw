<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('filename')
            ->add('nom')
            ->add('reference')
            ->add('categorie')
            ->add('gencod')
            ->add('prix_base')
            ->add('prix_final')
            ->add('etat',ChoiceType::class, [
                'label' => 'Etat',
                'required' => false,
                'choices'=> [
                    'Disponible' => true,
                    'Non disponible' =>false
                ],
                'placeholder' => false,
                'expanded' =>true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
