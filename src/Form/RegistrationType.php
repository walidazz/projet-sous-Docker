<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo',TextType::class, ['attr' => ['placeholder' => 'Entrez un pseudo', 'required' => true] ])
            ->add('email', EmailType::class,  ['attr' =>  ['placeholder' => 'Entrez une adresse mail', 'required' => true]])
            ->add('password', PasswordType::class,  ['attr' =>  ['placeholder' => 'Entrez un mot de passe', 'required' => true]] )
            ->add('passwordConfirm', PasswordType::class,  ['attr' =>  ['placeholder' => 'Confirmez votre mot de passe', 'required' => true]])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
