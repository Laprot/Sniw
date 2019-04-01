<?php
// src/FormUserType.php
namespace App\Form;

use App\Entity\Groupe;
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

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Titre',ChoiceType::class,[
                'label' => 'Titre',
                'required' => false,
                'choices'=> [
                    'M' => true,
                    'F'=>false
                ],
                'placeholder' => false,
                'expanded' =>true
            ])
            ->add('societe', TextType::class, [
                'label'=> 'Société *',
                'required'=>false
            ])
            ->add('id_groupe', EntityType::class, [
                'class'=> Groupe::class,
                'label' => 'Accès groupe(s) *',
                'multiple'=>true,
                'expanded'=>true,
                'choice_label' => 'nom',
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
                'label' => 'Nom de compte *',
                'required'=>false
            ])
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'required'=>true,
                'first_options'  => array('label' => 'Mot de passe *'),
                'second_options' => array('label' => 'Répéter le mot de passe *'),
            ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}