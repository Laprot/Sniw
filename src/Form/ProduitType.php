<?php

namespace App\Form;

use App\Entity\Manufacturer;
use App\Entity\Produit;
use App\Form\Type\ManufacturerType;
use App\Repository\ManufacturerRepository;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
            ->add('id_manufacturer',ManufacturerType::class, [
                'label' => 'Fabricant'
            ])
            ->add('weight')
            ->add('unite')
            ->add('prix_unite')
            ->add('feature', null ,[
                'label' => 'Fonctionnalités'
            ])
            ->add('upc')

            ->add('prix_base', null, [
                'required' => false
            ])
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
