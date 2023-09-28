<?php

namespace App\Form;

use App\Entity\Entreprise;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom <span style="color: red">*</span>',
                'label_html' => true,
                'attr' => [
                    'placeholder' => 'Nom de l\'utilisateur'
                ],
                'required' => true,
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom <span style="color: red">*</span>',
                'label_html' => true,
                'attr' => [
                    'placeholder' => 'Prénom de l\'utilisateur'
                ],
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Mail',
                'attr' => [
                    'placeholder' => 'Mail de l\'utilisateur'
                ],
                'required' => false,
            ])
            ->add('entreprise', EntityType::class, [
                'class' => Entreprise::class,
                'choice_label' => 'nom',
                'label' => 'Site',
                'attr' => [
                    'class' => 'selectpicker',
                ],
                'placeholder' => 'Choisir un site',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
