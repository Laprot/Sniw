<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\CommandeTypeProduits;
use App\Repository\CommandeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeTypeProduitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('commande', EntityType::class ,[
                'class' => Commande::class,
                'label' => 'À partir de la commande : ',
                'query_builder' => function (CommandeRepository $c) {
                    $queryBuilder = $c->createQueryBuilder('c');
                    $query = $queryBuilder
                        ->where('c.nom = :nom')
                        ->setParameter('nom', 'admin')
                        ->orderBy('c.id', 'ASC');
                    return $query;
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CommandeTypeProduits::class,
        ]);
    }
}
