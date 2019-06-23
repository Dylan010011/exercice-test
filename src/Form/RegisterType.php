<?php

namespace App\Form;

use App\Form\Facto;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class RegisterType extends Facto
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->facto('Prénom', 'Votre prénom'))
            ->add('lastName', TextType::class, $this->facto('Nom', 'Votre nom de famille'))
            ->add('email', EmailType::class, $this->facto('Email', 'Votre adresse email'))
            ->add('picture', UrlType::class, $this->facto('Une photo (Optionnelle)', 'Votre photo', ['required' => false]))
            ->add('hash', PasswordType::class, $this->facto('Tapez votre mot de passe', 'Votre mot de passe'))
            ->add('passwordConfirm', PasswordType::class, $this->facto('Confirmation du mot de passe', 'Retapez votre mot de passe'))
            ->add('introduction', TextType::class, $this->facto('Introduction', 'Introduction sur vous'))
            ->add('description', TextareaType::class, $this->facto('Description', 'Decrivez-vous'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
