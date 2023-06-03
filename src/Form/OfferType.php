<?php

namespace App\Form;

use App\Entity\Offer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label',TextType::class,
            [
                // 'label' => false,
            ])
            ->add('moneyPerHour',TextType::class,
            [
                // 'label' => false,
            ])
            ->add('description',TextareaType::class,
            [
                // 'label' => false,
            ])
             ->add('startAt',DateType::class,[
                'format' => 'dd-MM-yyyy',
                'widget' => 'choice',
                'placeholder' => 
                [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ],
                'required' => false,
                ])
             
             
            ->add('endAt',DateType::class,[
                'placeholder' => 
                [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ],
                'format' => 'dd-MM-yyyy',
                'required' => false,
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
