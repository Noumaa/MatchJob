<?php

namespace App\Form\Resume;

use App\Entity\Resume\Course;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, [
                'attr' => [
                    'placeholder' => 'Formation BTP'
                ]
            ])

            ->add('startedAt')
            ->add('endedAt')

            ->add('location', TextType::class, [
                'attr' => [
                    'placeholder' => 'École 48 - Paris (75)'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
