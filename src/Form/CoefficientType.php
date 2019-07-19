<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Groupe;
use App\Entity\Coefficient;
use App\Repository\CategorieRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoefficientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('new_coeff',null , [
                'label' => 'Ajouter un coefficient'
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
                'multiple'=>true,
                'expanded'=>true,
                'choice_label' => 'nom',
                'query_builder' => function (CategorieRepository $c) {
                    $queryBuilder =$c->createQueryBuilder('c');
                    $query = $queryBuilder
                        ->where($queryBuilder->expr()->isNull('c.id_parent'))
                        ->orderBy('c.id','ASC');
                    dump($query);
                    die();
                    return $query;
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Coefficient::class,
        ]);
    }
}
