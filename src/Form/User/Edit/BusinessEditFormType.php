<?php

namespace App\Form\User\Edit;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class BusinessEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', UserEditFormType::class, [
                'data_class' => User::class,
            ])

            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez renseigner le nom de l\'organisation',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Le nom de l\'organisation ne peut pas faire moins de {{ limit }} caractères',
                    ]),
                ],
            ])

            ->add('siret', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez renseigner le siret',
                    ]),
                    // TODO: validator
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
