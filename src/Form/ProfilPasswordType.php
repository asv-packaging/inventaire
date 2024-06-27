<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => "Les deux mots de passe doivent correspondre !",
                'first_options' => [
                    'label' => "Nouveau mot de passe <span style='color: red'>*</span>",
                    'label_html' => true,
                    'attr' => [
                        'placeholder' => "Votre nouveau mot de passe",
                    ],
                ],
                'second_options' => [
                    'label' => "Confirmation du nouveau mot de passe <span style='color: red'>*</span>",
                    'label_html' => true,
                    'attr' => [
                        'placeholder' => "Confirmez votre nouveau mot de passe",
                    ],
                ],
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
