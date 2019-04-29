<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Groupe;
use App\Entity\Reduction;
use App\Repository\CategorieRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReductionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('new_reduction',null , [
                'label' => 'Ajouter une réduction en respectant le format (21,56% ==> 0.2156)'
            ])
            ->add('groupes', EntityType::class, [
                'class' => Groupe::class,
                'multiple' =>false,
                'expanded' =>false,
                'label' => 'Pour le groupe'
            ])
            ->add('categories', EntityType::class, [
                'class' => Categorie::class,
                'label' => 'Pour la catégorie',
                'multiple'=>false,
                'expanded'=>false,
                'choice_label' => 'nom',
                'query_builder' => function (CategorieRepository $c) {
                    $queryBuilder =$c->createQueryBuilder('c');
                    $query = $queryBuilder
                        ->where($queryBuilder->expr()->isNull('c.id_parent'))
                        ->orderBy('c.id','ASC');
                    return $query;
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reduction::class,
        ]);
    }
}
