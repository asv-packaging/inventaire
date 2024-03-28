<?php

namespace App\Form;

use App\Entity\Entreprise;
use App\Entity\Etat;
use App\Entity\Fournisseur;
use App\Entity\TelephonePortable;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TelephonePortableFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ligne', TextType::class, [
                'label' => 'Ligne du téléphone <span style="color: red">*</span>',
                'label_html' => true,
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
                'required' => false
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
            ->add('imei1', TextType::class, [
                'label' => 'Numéro IMEI 1',
                'attr' => [
                    'placeholder' => 'Numéro IMEI 1'
                ],
                'required' => false,
            ])
            ->add('imei2', TextType::class, [
                'label' => 'Numéro IMEI 2',
                'attr' => [
                    'placeholder' => 'Numéro IMEI 2'
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
                    'rows' => '7'
                ],
                'required' => false,
            ])
            ->add('utilisateur', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => function(Utilisateur $utilisateur) {
                    return $utilisateur->getNom() . ' ' . $utilisateur->getPrenom();
                },
                'query_builder' => function($er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.id', 'DESC');
                },
                'required' => false,
                'placeholder' => 'Choisir un utilisateur',
                'attr' => [
                    'class' => 'selectpicker',
                ]
            ])
            ->add('etat', EntityType::class, [
                'label' => 'État <span style="color: red">*</span>',
                'label_html' => true,
                'class' => Etat::class,
                'choice_label' => 'nom',
                'query_builder' => function($er) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.id', 'DESC');
                },
                'required' => true,
                'placeholder' => 'Choisir un état',
                'attr' => [
                    'class' => 'selectpicker',
                ]
            ])
            ->add('entreprise', EntityType::class, [
                'label' => 'Site',
                'class' => Entreprise::class,
                'choice_label' => 'nom',
                'query_builder' => function($er) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.id', 'DESC');
                },
                'required' => true,
                'placeholder' => 'Choisir un site',
                'attr' => [
                    'class' => 'selectpicker',
                ]
            ])
            ->add('fournisseur', EntityType::class, [
                'label' => 'Fournisseur',
                'class' => Fournisseur::class,
                'choice_label' => 'nom',
                'query_builder' => function($er) {
                    return $er->createQueryBuilder('f')
                        ->orderBy('f.id', 'DESC');
                },
                'required' => true,
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
            'data_class' => TelephonePortable::class,
        ]);
    }
}
