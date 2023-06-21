<?php

namespace App\Form;

use App\Entity\Emplacement;
use App\Entity\Etat;
use App\Entity\Serveur;
use App\Entity\Stockage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServeurFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du serveur',
                'attr' => [
                    'placeholder' => 'Nom du serveur'
                ]
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
                    'placeholder' => '192.168.1.1'
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
                    'placeholder' => 'Mémoire'
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
            ->add('os', TextType::class, [
                'label' => 'Système d\'exploitation',
                'attr' => [
                    'placeholder' => 'Système d\'exploitation'
                ],
                'required' => false,
            ])
            ->add('physique', ChoiceType::class, [
                'label' => 'Type',
                'choices' => [
                    'Physique' => true,
                    'Virtuel' => false,
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
                'label' => 'Date de fin garantie',
                'attr' => [
                    'class' => 'datetimepicker',
                    'placeholder' => 'Date de fin garantie'
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
                'class' => Stockage::class,
                'choice_label' => 'nom',
                'required' => false,
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
            'data_class' => Serveur::class,
        ]);
    }
}
