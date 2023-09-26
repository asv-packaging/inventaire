<?php

namespace App\Form;

use App\Entity\Emplacement;
use App\Entity\Entreprise;
use App\Entity\Etat;
use App\Entity\Fournisseur;
use App\Entity\Serveur;
use App\Entity\Stockage;
use App\Entity\SystemeExploitation;
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
                'label' => 'Nom du serveur <span style="color: red">*</span>',
                'label_html' => true,
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
            ->add('physique', ChoiceType::class, [
                'label' => 'Type <span style="color: red">*</span>',
                'label_html' => true,
                'placeholder' => 'Choisir un type',
                'choices' => [
                    'Physique' => true,
                    'Virtuel' => false,
                ],
                'attr' => [
                    'class' => 'selectpicker',
                ],
                'required' => false,
            ])
            ->add('date_contrat', TextType::class, [
                'label' => 'Date de contrat',
                'attr' => [
                    'class' => 'datetimepicker',
                    'placeholder' => 'Date de contrat'
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
                'label' => 'Emplacement <span style="color: red">*</span>',
                'label_html' => true,
                'class' => Emplacement::class,
                'choice_label' => 'nom',
                'required' => true,
                'placeholder' => 'Choisir un emplacement',
                'attr' => [
                    'class' => 'selectpicker',
                ]
            ])
            ->add('etat', EntityType::class, [
                'label' => 'État <span style="color: red">*</span>',
                'label_html' => true,
                'class' => Etat::class,
                'choice_label' => 'nom',
                'required' => true,
                'placeholder' => 'Choisir un état',
                'attr' => [
                    'class' => 'selectpicker',
                ]
            ])
            ->add('stockage', EntityType::class, [
                'label' => 'Type de stockage',
                'class' => Stockage::class,
                'choice_label' => 'nom',
                'required' => false,
                'placeholder' => 'Choisir un type de stockage',
                'attr' => [
                    'class' => 'selectpicker',
                ]
            ])
            ->add('stockage_type', ChoiceType::class, [
                'label' => 'Type de disque dur',
                'required' => false,
                'choices' => [
                    'HDD' => 'HDD',
                    'SSD' => 'SSD',
                ],
                'placeholder' => 'Choisir un type de disque dur',
                'attr' => [
                    'class' => 'selectpicker',
                ]
            ])
            ->add('entreprise', EntityType::class, [
                'label' => 'Site',
                'class' => Entreprise::class,
                'choice_label' => 'nom',
                'required' => false,
                'placeholder' => 'Choisir un site',
                'attr' => [
                    'class' => 'selectpicker',
                ]
            ])
            ->add('fournisseur', EntityType::class, [
                'label' => 'Fournisseur',
                'class' => Fournisseur::class,
                'choice_label' => 'nom',
                'required' => false,
                'placeholder' => 'Choisir un fournisseur',
                'attr' => [
                    'class' => 'selectpicker',
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
