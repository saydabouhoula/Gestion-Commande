<?php

namespace App\Form;

use App\Entity\CategorieProduit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Range;


class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'constraints' => [
                    new NotBlank(['message' => 'The  field is required']),
                    new Length([
                        'min' => 3,
                        'max' => 15,
                        'minMessage' => 'The  field must be at least {{ limit }} characters long',
                        'maxMessage' => 'The  field cannot be longer than {{ limit }} characters',
                    ]),
                ],
            ])

            ->add('descr', null, [
                'constraints' => [
                    new NotBlank(['message' => 'The  field is required']),
                    new Length([
                        'min' => 3,
                        'max' => 50,
                        'minMessage' => 'The  field must be at least {{ limit }} characters long',
                        'maxMessage' => 'The  field cannot be longer than {{ limit }} characters',
                    ]),
                ],
            ])

            ->add('marque', null, [
                'constraints' => [
                    new NotBlank(['message' => 'The  field is required']),
                    new Length([
                        'min' => 3,
                        'max' => 50,
                        'minMessage' => 'The  field must be at least {{ limit }} characters long',
                        'maxMessage' => 'The  field cannot be longer than {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('model', null, [
                'constraints' => [
                    new NotBlank(['message' => 'The  field is required']),
                    new Length([
                        'min' => 3,
                        'max' => 50,
                        'minMessage' => 'The  field must be at least {{ limit }} characters long',
                        'maxMessage' => 'The  field cannot be longer than {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('img', null, [
                'constraints' => [
                    new NotBlank(['message' => 'The  field is required']),
                ],
            ])


            ->add('img2', null, [
                'constraints' => [
                    new NotBlank(['message' => 'The  field is required']),
                ],
            ])


            ->add('prix', null, [
                'constraints' => [
                    new NotBlank(['message' => 'NOT NULL PLEASE']),
                    new Range([
                        'min' => 1,
                        'notInRangeMessage' => 'You must be > {{ min }}'
                    ])
                ]
            ])

            ->add('tauxRemise', null, [
                'constraints' => [
                    new NotBlank(['message' => 'NOT NULL PLEASE']),
                    new Range([
                        'min' => 0,
                        'max' => 50,
                        'minMessage' => 'The  field must be at least {{ limit }} characters long',
                        'maxMessage' => 'The  field cannot be longer than {{ limit }} characters',
                    ])
                ],
            ])

            ->add('stock', null, [
                'constraints' => [
                    new NotBlank(['message' => 'NOT NULL PLEASE']),
                    new Range([
                        'min' => 0,
                        'notInRangeMessage' => 'You must be between {{ min }}'
                    ])
                ],
            ])

            ->add('nomCat', EntityType::class, [
                'class' => CategorieProduit::class,
                'choice_label' => 'nomCat',
                'constraints' => [
                    new NotBlank(['message' => 'The  field is required'])
                ],

            ])


            ->add('categ', null, [
                'constraints' => [
                    new NotBlank(['message' => 'The  field is required']),
                    new Length([
                        'min' => 3,
                        'max' => 50,
                        'minMessage' => 'The  field must be at least {{ limit }} characters long',
                        'maxMessage' => 'The  field cannot be longer than {{ limit }} characters',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
