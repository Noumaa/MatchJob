<?php

namespace App\Form\Authentication;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class BusinessFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', UserType::class)
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom de l\'organisation ne peut pas être vide',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Le nom de l\'organisation doit faire au moins {{ limit }} caractères',
                    ]),
                ],
            ])

            // TODO: validator
            ->add('siret', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le SIRET ne peut pas être vide',
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
