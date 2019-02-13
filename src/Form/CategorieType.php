<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorieType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id')
            ->add('nom')
            ->add('id_parent',EntityType::class,[
                    'required' =>false,
                    'attr' => ['id' => 'data-value'],
                    'class' => Categorie::class,
                    'choice_label' => 'nom',
                    'expanded' => false,
                    'multiple' => false,
                    'group_by' => 'id_parent',
                    'label' => 'Catégorie parente',
                    'query_builder' => function (CategorieRepository $c) {
                        $queryBuilder = $c->createQueryBuilder('c');
                        $query = $queryBuilder
                            ->where($queryBuilder->expr()->isNotNull('c.id_parent'))
                            ->orderBy('c.id_parent', 'ASC');
                        return $query;
                    }
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}