<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Features;
use App\Entity\Produit;
use App\Form\Type\ManufacturerType;



use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{


    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $features = $this->em->getRepository(Features::class)->findAll();

        $builder
            ->add('nom')
            ->add('reference')
            ->add('categories',EntityType::class,[
                'required' =>false,
                'attr' => ['id' => 'data-value'],
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'expanded' => true,
                'multiple' => true,
                'group_by' => 'id_parent',
                'label' => 'Catégorie parente',
            ])

            ->add('Gencod',null, [
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

            ->add('conditionnement', null, [
                'label' => $features[0]
            ])
            ->add('unite_par_carton', null, [
                'label' => $features[1]
            ])
            ->add('nb_carton_palette', null, [
                'label' => $features[2]
            ])
            ->add('dlv_garantie', null, [
                'label' => $features[3]
            ])
            ->add('dlv_theorique', null, [
                'label' => $features[4]
            ])
            ->add('unite_par_couche', null, [
                'label' => $features[5]
            ])
            ->add('produit_bio', ChoiceType::class, [
                'label' => $features[6],
                'required' => false,
                'choices'=> [
                    'Oui' => true,
                    'Non' =>false
                ],
                'placeholder' => false,
                'expanded' =>true
            ])
            ->add('produit_nouveau', ChoiceType::class, [
                'label' => $features[7],
                'required' => false,
                'choices'=> [
                    'Oui' => true,
                    'Non' =>false
                ],
                'placeholder' => false,
                'expanded' =>true
            ])
            ->add('produit_belle_france', ChoiceType::class, [
                'label' => $features[8],
                'required' => false,
                'choices'=> [
                    'Oui' => true,
                    'Non' =>false
                ],
                'placeholder' => false,
                'expanded' =>true
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
