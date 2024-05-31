<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name on Card',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter the name on the card',
                    ]),
                ],
            ])
            ->add('cardNumber', TextType::class, [
                'label' => 'Card Number',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter the card number',
                    ]),
                    new Type([
                        'type' => 'numeric',
                        'message' => 'Please enter a valid card number',
                    ]),
                    /*new CardScheme([
                        'schemes' => ['visa', 'mastercard'],
                        'message' => 'Please enter a valid Visa or Mastercard card number',
                    ]),*/
                ],
            ])
            ->add('expMonth', IntegerType::class, [
                'label' => 'Expiration Month',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter the expiration month',
                    ]),
                    new Type([
                        'type' => 'integer',
                        'message' => 'Please enter a valid expiration month',
                    ]),
                ],
            ])
            ->add('expYear', IntegerType::class, [
                'label' => 'Expiration Year',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter the expiration year',
                    ]),
                    new Type([
                        'type' => 'integer',
                        'message' => 'Please enter a valid expiration year',
                    ]),
                ],
            ])
            ->add('cvc', IntegerType::class, [
                'label' => 'CVC',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter the CVC',
                    ]),
                    new Type([
                        'type' => 'integer',
                        'message' => 'Please enter a valid CVC',
                    ]),
                ],
            ])
            ->add('stripeToken', HiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PaymentData::class,
        ]);
    }
}
