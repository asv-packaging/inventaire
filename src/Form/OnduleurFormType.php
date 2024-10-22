<?php

namespace App\Form;

use App\Entity\Emplacement;
use App\Entity\Entreprise;
use App\Entity\Etat;
use App\Entity\Fournisseur;
use App\Entity\Onduleur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OnduleurFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de l\'onduleur <span style="color: red">*</span>',
                'label_html' => true,
                'attr' => [
                    'placeholder' => 'Nom de l\'onduleur'
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
            ->add('capacite', IntegerType::class, [
                'label' => 'Capacité en Watt',
                'attr' => [
                    'placeholder' => 'Capacité en Watt',
                    'min' => 1
                ],
                'required' => false,
            ])
            ->add('type_prise', ChoiceType::class, [
                'label' => 'Type de prise',
                'placeholder' => 'Choisir un type de prise',
                'attr' => [
                    'class' => 'selectpicker'
                ],
                'choices' => [
                    'FR' => 'FR',
                    'DIN' => 'DIN',
                    'IEC' => 'IEC',
                ]
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
                'label' => 'Date de remplacement',
                'attr' => [
                    'class' => 'datetimepicker',
                    'placeholder' => 'Date de remplacement'
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
                'query_builder' => function($er) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.id', 'DESC');
                },
                'placeholder' => 'Choisir un emplacement',
                'attr' => [
                    'class' => 'selectpicker'
                ],
                'required' => true,
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
                'placeholder' => 'Choisir un état',
                'attr' => [
                    'class' => 'selectpicker'
                ],
                'required' => true,
            ])
            ->add('fournisseur', EntityType::class, [
                'label' => 'Fournisseur',
                'class' => Fournisseur::class,
                'choice_label' => 'nom',
                'query_builder' => function($er) {
                    return $er->createQueryBuilder('f')
                        ->orderBy('f.id', 'DESC');
                },
                'placeholder' => 'Choisir un fournisseur',
                'attr' => [
                    'class' => 'selectpicker'
                ],
                'required' => false,
            ])
            ->add('entreprise', EntityType::class, [
                'label' => 'Site',
                'class' => Entreprise::class,
                'choice_label' => 'nom',
                'query_builder' => function($er) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.id', 'DESC');
                },
                'placeholder' => 'Choisir un site',
                'attr' => [
                    'class' => 'selectpicker'
                ],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Onduleur::class,
        ]);
    }
}
