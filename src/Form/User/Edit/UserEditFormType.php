<?php

namespace App\Form\User\Edit;

use App\Entity\User;
use App\Form\LocationFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('profilePicture', FileType::class, [
                'mapped' => false,
                'required' => false,
            ])

            // FIXME: maybe need a not blank constraint
            ->add('location', LocationFormType::class, [
                'data_class' => User::class,
            ])
            
            // TODO: validate (askip)
            ->add('phone')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'inherit_data' => true,
        ]);
    }
}
