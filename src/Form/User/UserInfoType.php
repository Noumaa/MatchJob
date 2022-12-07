<?php

namespace App\Form\User;

use App\Entity\UserInfo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            // Todo : map this thing
            ->add('dateOfBirth', TextType::class, [
                'mapped' => false
            ])
            ->add('commonInfo', CommonInfoType::class, [
                'data_class' => UserInfo::class,
            ])
            // Todo : map this thing too
            ->add('cv', FileType::class, [
                "mapped" => false
            ])
            ->add('profesionnalStatus')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // 'inherit_data' => true,
            'data_class' => UserInfo::class,
        ]);
    }
}
