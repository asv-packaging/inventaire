<?php

namespace App\Form;

use App\Entity\Emplacement;
use App\Entity\Entreprise;
use App\Entity\Etat;
use App\Entity\Fournisseur;
use App\Entity\PcFixe;
use App\Entity\Stockage;
use App\Entity\SystemeExploitation;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
                'label' => 'Mémoire',
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
            ->add('stockage_type', ChoiceType::class, [
                'label' => 'Type de disque dur',
                'placeholder' => 'Choisir un type de disque dur',
                'choices' => [
                    'HDD' => 'HDD',
                    'SSD' => 'SSD',
                ],
                'attr' => [
                    'class' => 'selectpicker',
                ],
                'required' => false,
            ])
            ->add('systeme_exploitation', EntityType::class, [
                'label' => 'Système d\'exploitation',
                'class' => SystemeExploitation::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un système d\'exploitation',
                'attr' => [
                    'class' => 'selectpicker',
                ],
                'required' => false,
            ])
            ->add('date_installation', TextType::class, [
                'label' => 'Date d\'installation',
                'attr' => [
                    'class' => 'datetimepicker',
                    'placeholder' => 'Date d\'installation'
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
                'placeholder' => 'Choisir un utilisateur',
                'required' => false,
                'attr' => [
                    'class' => 'selectpicker',
                ]
            ])
            ->add('emplacement', EntityType::class, [
                'label' => 'Emplacement',
                'class' => Emplacement::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un emplacement',
                'required' => true,
                'attr' => [
                    'class' => 'selectpicker',
                ]
            ])
            ->add('etat', EntityType::class, [
                'label' => 'État',
                'class' => Etat::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un état',
                'required' => true,
                'attr' => [
                    'class' => 'selectpicker',
                ]
            ])
            ->add('stockage', EntityType::class, [
                'label' => 'Type de stockage',
                'class' => Stockage::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un type de stockage',
                'attr' => [
                    'class' => 'selectpicker',
                ],
                'required' => false,
            ])
            ->add('entreprise', EntityType::class, [
                'label' => 'Site',
                'class' => Entreprise::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un site',
                'attr' => [
                    'class' => 'selectpicker',
                ],
                'required' => false,
            ])
            ->add('fournisseur', EntityType::class, [
                'label' => 'Fournisseur',
                'class' => Fournisseur::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un fournisseur',
                'attr' => [
                    'class' => 'selectpicker',
                ],
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
