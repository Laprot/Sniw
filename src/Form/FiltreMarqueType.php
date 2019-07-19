<?php

namespace App\Form;

use App\Entity\Filtre;
use App\Entity\Manufacturer;
use App\Repository\ManufacturerRepository;
use App\Repository\ProduitRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltreMarqueType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $categorie = $options['categorie'];

        $builder
            ->add('marque', EntityType::class, [
                'class' => Manufacturer::class,
                'choice_label' => 'nom',
                'label' => false,
                'required' => false,
                'multiple'=>true,
                'expanded'=>true,
                'query_builder' => function(ManufacturerRepository $manu) use ($categorie){
                    $queryBuilder =$manu->createQueryBuilder('m');
                    $query = $queryBuilder
                        ->leftJoin('m.produits', 'prod')
                        ->andWhere('prod.id_manufacturer = m.id')
                        ->innerJoin('prod.categories','c','WITH','c.id = :categorie')
                        ->orderBy('m.nom','ASC')
                        ->setParameter('categorie', $categorie);
                    ;
                    return $query;
                }

            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Filtre::class,
            'method' => 'get',
            'csrf_protection' => false,
            'categorie' => null
        ]);
    }

}
