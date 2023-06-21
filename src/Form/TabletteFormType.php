<?php

namespace App\Form;

use App\Entity\Emplacement;
use App\Entity\Etat;
use App\Entity\Tablette;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TabletteFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la tablette',
                'attr' => [
                    'placeholder' => 'Nom de la tablette'
                ],
                'required' => true,
            ])
            ->add('marque', TextType::class, [
                'label' => 'Nom de la marque',
                'attr' => [
                    'placeholder' => 'Nom de la marque'
                ],
                'required' => false,
            ])
            ->add('modele', TextType::class, [
                'label' => 'Nom du modèle',
                'attr' => [
                    'placeholder' => 'Nom du modèle'
                ],
                'required' => false,
            ])
            ->add('numero_serie', TextType::class, [
                'label' => 'Numéro de série',
                'attr' => [
                    'placeholder' => 'Numéro de série'
                ],
                'required' => false,
            ])
            ->add('ip', TextType::class, [
                'label' => 'Adresse IP',
                'attr' => [
                    'placeholder' => 'Adresse IP'
                ],
                'required' => false,
            ])
            ->add('date_achat', TextType::class, [
                'label' => 'Date d\'achat',
                'attr' => [
                    'class' => 'datetimepicker',
                    'placeholder' => 'Date d\'achat'
                ],
                'required' => false,
            ])
            ->add('date_garantie', TextType::class, [
                'label' => 'Date de garantie',
                'attr' => [
                    'class' => 'datetimepicker',
                    'placeholder' => 'Date de garantie'
                ],
                'required' => false,
            ])
            ->add('commentaire', TextareaType::class, [
                'label' => 'Commentaire',
                'attr' => [
                    'placeholder' => 'Commentaire',
                    'rows' => '7'
                ],
                'required' => false,
            ])
            ->add('utilisateur', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => function(Utilisateur $utilisateur) {
                    return $utilisateur->getNom() . ' ' . $utilisateur->getPrenom();
                },
                'required' => false,
                'attr' => [
                    'class' => 'js-choice',
                    'data-options' => '{"removeItemButton":true,"placeholder":true}',
                ]
            ])
            ->add('emplacement', EntityType::class, [
                'class' => Emplacement::class,
                'choice_label' => 'nom',
                'required' => true,
                'attr' => [
                    'class' => 'js-choice',
                    'data-options' => '{"removeItemButton":true,"placeholder":true}',
                ]
            ])
            ->add('etat', EntityType::class, [
                'class' => Etat::class,
                'choice_label' => 'nom',
                'required' => true,
                'attr' => [
                    'class' => 'js-choice',
                    'data-options' => '{"removeItemButton":true,"placeholder":true}',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tablette::class,
        ]);
    }
}
