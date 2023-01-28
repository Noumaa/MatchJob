<?php

namespace App\Form;

use App\Entity\Offer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label',TextareaType::class,
            [
                'disabled' => true,
                // 'label' => false,
            ])
            ->add('moneyPerHour',TextareaType::class,
            [
                'disabled' => true,
                // 'label' => false,
            ])
            ->add('description',TextareaType::class,
            [
                'disabled' => true,
                // 'label' => false,
            ])
             ->add('startAt',DateType::class,[
                'disabled' => true,
                'placeholder' => 
                [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ],
                'format' => 'dd-MM-yyyy',
                ])
             
             
            ->add('endAt',DateType::class,[
                'disabled' => true,
                'placeholder' => 
                [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ],
                'format' => 'dd-MM-yyyy',
                ])
            //->add('createdAt')
            //->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offer::class,
        ]);
    }
}
