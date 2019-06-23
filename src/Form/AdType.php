<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\Facto;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends Facto
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('title', TextType::class, $this->facto('Titre', 'Titre de l\'annonce'))
            ->add('slug', TextType::class, $this->facto('URL de l\'annonce', 'URL', ['required' => false]))
            ->add('price', MoneyType::class, $this->facto('Prix', 'Prix de la chambre'))
            ->add('introduction', TextType::class, $this->facto('Introduction', 'Introduction de l\'annonce'))
            ->add('content', TextareaType::class, $this->facto('Contenu', 'Contenu de l\'annonce'))
            ->add('coverImage', UrlType::class, $this->facto('URL de l\'image', 'Tapez le lien de votre image'))
            ->add('rooms', IntegerType::class, $this->facto('Nombre de chambres', 'Le nombre de chambres disponibles'))
            ->add('images', CollectionType::class, ['entry_type' => ImageType::class, 'allow_add' => true, 'allow_delete' => true])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
