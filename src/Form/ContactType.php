<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
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
            ->add('code_postal',TextType::class, [
                'label' =>'Code Postal',
                'required'=>false
            ])
            ->add('adresse',TextType::class, [
                'label'=> 'Adresse',
                'required'=>false
            ])
            ->add('pays',CountryType::class,[
                'label' =>'Pays',
                'required'=>false
            ])
            ->add('ville',TextType::class, [
                'label' => 'Ville',
                'required' => false
            ])
            ->add('telephone',TextType::class, [
                'label' => 'Téléphone *',
                'required' =>true
            ])
            ->add('email',EmailType::class, [
                'label' => 'Email *',
                'required'=>true
            ])
            ->add('grossiste_bool',ChoiceType::class,[
                'label' => 'Etes-vous grossiste ?',
                'required' => false,
                'choices'=> [
                    'Oui' => true,
                    'Non'=>false
                ],
                'placeholder' => false,
                'expanded' =>true
            ])
            ->add('supermarche_bool',ChoiceType::class,[
                'label' => 'Possédez-vous un supermarché ?',
                'required' => false,
                'choices'=> [

                    'Oui' => true,
                    'Non'=>false,
                ],
                'placeholder' => false,
                'expanded'=>true
            ])
            ->add('demande',TextareaType::class, [
                'label'=>'Votre demande : *',
                'required'=>true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
