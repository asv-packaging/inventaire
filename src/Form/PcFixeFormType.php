<?php

namespace App\Form;

use App\Entity\Emplacement;
use App\Entity\Etat;
use App\Entity\PcFixe;
use App\Entity\Stockage;
use App\Entity\SystemeExploitation;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PcFixeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du PC Fixe',
                'attr' => [
                    'placeholder' => 'Nom du PC Fixe'
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
            ->add('processeur', TextType::class, [
                'label' => 'Nom du processeur',
                'attr' => [
                    'placeholder' => 'Nom du processeur'
                ],
                'required' => false,
            ])
            ->add('memoire', IntegerType::class, [
                'label' => 'Mémoire (en Go)',
                'attr' => [
                    'placeholder' => 'Mémoire (en Go)'
                ],
                'required' => false,
            ])
            ->add('stockage_nombre', IntegerType::class, [
                'label' => 'Stockage',
                'attr' => [
                    'placeholder' => 'Stockage'
                ],
                'required' => false,
            ])
            ->add('systeme_exploitation', EntityType::class, [
                'label' => 'Système d\'exploitation',
                'class' => SystemeExploitation::class,
                'choice_label' => 'nom',
                'attr' => [
                    'class' => 'js-choice',
                    'data-options' => '{"removeItemButton":true,"placeholder":true}',
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
                'label' => 'Date de fin de garantie',
                'attr' => [
                    'class' => 'datetimepicker',
                    'placeholder' => 'Date de fin de garantie'
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
                'label' => 'Utilisateur',
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
                'label' => 'Emplacement',
                'class' => Emplacement::class,
                'choice_label' => 'nom',
                'required' => true,
                'attr' => [
                    'class' => 'js-choice',
                    'data-options' => '{"removeItemButton":true,"placeholder":true}',
                ]
            ])
            ->add('etat', EntityType::class, [
                'label' => 'État',
                'class' => Etat::class,
                'choice_label' => 'nom',
                'required' => true,
                'attr' => [
                    'class' => 'js-choice',
                    'data-options' => '{"removeItemButton":true,"placeholder":true}',
                ]
            ])
            ->add('stockage', EntityType::class, [
                'label' => 'Type de stockage',
                'attr' => [
                    'placeholder' => 'Type de stockage',
                    'class' => 'js-choice',
                    'data-options' => '{"removeItemButton":true,"placeholder":true}',
                ],
                'class' => Stockage::class,
                'choice_label' => 'nom',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PcFixe::class,
        ]);
    }
}
