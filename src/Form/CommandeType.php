<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status', ChoiceType::class, [
                'choices' => [
                    Commande::STATUS_INIT => Commande::STATUS_INIT,
                    Commande::STAUTUS_EXPEDIER => Commande::STAUTUS_EXPEDIER,
                    Commande::STAUTS_CONFIRMER => Commande::STAUTS_CONFIRMER,
                    Commande::STATUS_ANNULER => Commande::STATUS_ANNULER,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}