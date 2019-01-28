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
            ->add('gencod',null, [
                'label' => 'Gencod (EAN13)'
            ])
            ->add('description')
            ->add('short_description')
            ->add('profondeur')
            ->add('manufacturer')
            ->add('weight')
            ->add('unite')
            ->add('prix_unite')
            ->add('feature')
            ->add('upc')

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
