<?php

namespace App\Form;

use App\Entity\Ecran;
use App\Entity\Emplacement;
use App\Entity\Entreprise;
use App\Entity\Etat;
use App\Entity\Fournisseur;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EcranFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de l\'écran',
                'attr' => [
                    'placeholder' => 'Nom de l\'écran'
                ],
                'required' => true,
            ])
            ->add('marque', TextType::class, [
                'label' => 'Marque',
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
                    'placeholder' => 'Date de fin de garantie',
                    'class' => 'datetimepicker',
                ],
                'label' => 'Date de fin de garantie',
                'required' => false,
            ])
            ->add('commentaire', TextareaType::class, [
                'label' => 'Commentaire',
                'attr' => [
                    'placeholder' => 'Commentaire',
                    'rows' => '7',
                ],
                'required' => false,
            ])
            ->add('utilisateur', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => function(Utilisateur $utilisateur) {
                    return $utilisateur->getNom() . ' ' . $utilisateur->getPrenom();
                },
                'placeholder' => 'Choisir un utilisateur',
                'attr' => [
                    'class' => 'selectpicker',
                ],
                'required' => false,
            ])
            ->add('emplacement', EntityType::class, [
                'class' => Emplacement::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un emplacement',
                'attr' => [
                    'class' => 'selectpicker',
                ],
                'required' => true,
            ])
            ->add('etat', EntityType::class, [
                'label' => 'État',
                'class' => Etat::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un état',
                'attr' => [
                    'class' => 'selectpicker',
                ],
                'required' => true,
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
            ->add('entreprise', EntityType::class, [
                'label' => 'Nom du site',
                'class' => Entreprise::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un site',
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
            'data_class' => Ecran::class,
        ]);
    }
}
