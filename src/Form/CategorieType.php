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
            ->add('id_parent', EntityType::class, array(
                'label' => 'Catégorie parente',
                'required'  =>  false,
                'class' => Categorie::class,
                'choice_label'  => function(Categorie $categorie){
                    return $categorie->getNom();
                },
                'multiple'  => false,
                'expanded'  => true,
                'placeholder' => false,
                'query_builder' => function(EntityRepository $er){
                    return $er
                        ->createQueryBuilder('u')
                        ->addOrderBy('u.lft','ASC');
                },
            ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
