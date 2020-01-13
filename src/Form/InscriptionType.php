<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'required' => true,
                'label' =>'Votre nom',
                'attr' => ['placeholder' => 'Veuillez mettre votre nom']
            ])
            ->add('mail', EmailType::class, [
                'required' => true,
                'label' =>'Votre email',
                'attr' => ['placeholder' => 'Veuillez mettre votre email']
            ])
            ->add('pass', PasswordType::class, [
                'required' => true,
                'label' =>'Votre mot de passe',
                'attr' => ['placeholder' => 'Veuillez mettre votre mot de passe']
            ])
            ->add('address', TextType::class, [
                'required' => true,
                'label' =>'Votre adresse',
                'attr' => ['placeholder' => 'Veuillez mettre votre adresse']
            ])
            ->add('cp', TextType::class, [
                'required' => true,
                'label' =>'Votre code postal',
                'attr' => ['placeholder' => 'Veuillez mettre votre code postal']
            ])
            ->add('town', TextType::class, [
                'required' => true,
                'label' =>'Votre ville',
                'attr' => ['placeholder' => 'Veuillez mettre votre ville']
            ])
            ->add('phone', TextType::class, [
                'required' => true,
                'label' =>'Votre téléphone',
                'attr' => ['placeholder' => 'Veuillez mettre votre numéro de téléphone']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

