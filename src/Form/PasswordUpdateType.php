<?php

namespace App\Form;

use App\Form\Facto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordUpdateType extends Facto
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, $this->facto('Ancien mot de passe', 'Tapez votre ancien mot de passe'))
            ->add('newPassword', PasswordType::class, $this->facto('Nouveau mot de passe', 'Tapez votre nouveau mot de passe'))
            ->add('confirmPassword', PasswordType::class, $this->facto('Confirmez votre mot de passe', 'Veuillez confirmer votre nouveau mot de passe'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
