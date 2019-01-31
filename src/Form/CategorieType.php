<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
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
            ->add('id_parent', EntityType::class, array(
                'label' => 'Catégorie parent',
                'required'  =>  false,
                'class' => Categorie::class,
                'choice_label'  => function(Categorie $categorie){
                    return $categorie->getNom();
                },
                'multiple'  => false,
                'expanded'  => false,
                'query_builder' => function(CategorieRepository $er){
                    return $er
                        ->createQueryBuilder('node')
                        ->orderBy('node.nom', 'ASC');
                }
            ))
            ->add('nom');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
