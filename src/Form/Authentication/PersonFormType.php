<?php

namespace App\Form\Authentication;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PersonFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', UserType::class)
            ->add('firstName', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le prénom ne peut pas être vide',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Le prénom doit faire au moins {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('lastName', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom de famille ne peut pas être vide',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Le nom de famille doit faire au moins {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('dateOfBirth', BirthdayType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'La date de naissance ne peut pas être vide',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
