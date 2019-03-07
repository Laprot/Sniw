<?php
// src/FormAdresseUserType.php
namespace App\Form;

use App\Entity\Adresse;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class AdresseUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('societe', TextType::class, [
                'label'=> 'Société *',
                'required'=>true
            ])
            ->add('nom', TextType::class, [
                'label'=> 'Nom *',
                'required'=>true
            ])
            ->add('prenom', TextType::class, [
                'label'=>'Prénom *',
                'required'=>true
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email *'
            ])
            ->add('username', TextType::class, [
                'label' => 'Nom de compte *'
            ])
            ->add('code_postal', TextType::class, [
                'label' => 'Code postal *'
            ])
            ->add('adresse', TextType::class, [
                'label'=> 'Adresse *'
            ])
            ->add('ville', TextType::class ,[
                'label' => 'Ville *'
            ])
            -> add('pays', TextType::class, [
                'label'=> 'Pays *'
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Téléphone *'
            ])
            ->add('nomAdresse', TextType::class, [
                'label' => 'Donnez un titre à cette adresse *',
                'attr' => [
                    'placeholder' => 'ex : MonAdresse'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}