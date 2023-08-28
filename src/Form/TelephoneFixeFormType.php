<?php

namespace App\Form;

use App\Entity\Emplacement;
use App\Entity\Entreprise;
use App\Entity\Etat;
use App\Entity\Fournisseur;
use App\Entity\TelephoneFixe;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TelephoneFixeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ligne', TextType::class, [
                'label' => 'Ligne du téléphone',
                'attr' => [
                    'placeholder' => 'Ligne du téléphone'
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
            ->add('ip', TextType::class, [
                'label' => 'Adresse IP',
                'attr' => [
                    'placeholder' => 'Adresse IP'
                ],
                'required' => false,
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type de téléphone <span style="color: red">*</span>',
                'label_html' => true,
                'placeholder' => 'Choisir le type de téléphone',
                'attr' => [
                    'class' => 'selectpicker'
                ],
                'choices' => [
                    'Fixe' => 'Fixe',
                    'DECT' => 'DECT',
                ],
                'required' => true,
            ])
            ->add('numero_serie', TextType::class, [
                'label' => 'Numéro de série',
                'attr' => [
                    'placeholder' => 'Numéro de série'
                ],
                'required' => false,
            ])
            ->add('date_installation', TextType::class, [
                'attr' => [
                    'placeholder' => 'Date d\'installation',
                    'class' => 'datetimepicker',
                ],
                'label' => 'Date d\'installation',
                'required' => false,
            ])
            ->add('date_achat', TextType::class, [
                'attr' => [
                    'placeholder' => 'Date d\'achat',
                    'class' => 'datetimepicker',
                ],
                'label' => 'Date d\'achat',
                'required' => false,
            ])
            ->add('date_garantie', TextType::class, [
                'attr' => [
                    'placeholder' => 'Date de garantie',
                    'class' => 'datetimepicker',
                ],
                'label' => 'Date de garantie',
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
                'placeholder' => 'Choisir un utilisateur',
                'attr' => [
                    'class' => 'selectpicker',
                ]
            ])
            ->add('fournisseur', EntityType::class, [
                'class' => Fournisseur::class,
                'choice_label' => 'nom',
                'required' => false,
                'placeholder' => 'Choisir un fournisseur',
                'attr' => [
                    'class' => 'selectpicker',
                ]
            ])
            ->add('emplacement', EntityType::class, [
                'class' => Emplacement::class,
                'choice_label' => 'nom',
                'required' => false,
                'placeholder' => 'Choisir un emplacement',
                'attr' => [
                    'class' => 'selectpicker',
                ]
            ])
            ->add('etat', EntityType::class, [
                'class' => Etat::class,
                'choice_label' => 'nom',
                'required' => true,
                'label' => 'État <span style="color: red">*</span>',
                'label_html' => true,
                'placeholder' => 'Choisir un état',
                'attr' => [
                    'class' => 'selectpicker',
                ]
            ])
            ->add('entreprise', EntityType::class, [
                'class' => Entreprise::class,
                'choice_label' => 'nom',
                'required' => true,
                'label' => 'Site',
                'placeholder' => 'Choisir un site',
                'attr' => [
                    'class' => 'selectpicker',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TelephoneFixe::class,
        ]);
    }
}
