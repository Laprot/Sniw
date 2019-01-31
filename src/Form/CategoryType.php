<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;



use Doctrine\Common\Persistence\ObjectManager;


use App\Entity\Category;

class CategoryType extends AbstractType
{

    private $em;
    private $repo;

    public function __construct(ObjectManager $em) {

        $this->em = $em;
        $this->repo = $this->em->getRepository(Category::class);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('parent', EntityType::class, array(
                'label' => 'Catégorie parent',
                'required'  =>  false,
                'class' => Category::class,
                'choice_label'  => function(Category $category){

                    $prefix = str_repeat('-', $category->getLvl());

                    return $prefix . ' ' . $category->getTitle();
                },
                'multiple'  => false,
                'expanded'  => false,
                'query_builder' => function(EntityRepository $er){

                    return $er
                        ->createQueryBuilder('node')
                        ->orderBy('node.root, node.lft', 'ASC');
                }
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Category::class
        ));
    }



}