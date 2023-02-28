<?php

namespace App\Form\Resume;

use App\Entity\Resume\Experience;
use App\Entity\User;
use Doctrine\DBAL\Types\DateImmutableType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExperienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, [
                'attr' => [
                    'placeholder' => 'Chef opérateur'
                ]
            ])

            ->add('startedAt')
            ->add('endedAt')

            ->add('location', TextType::class, [
                'attr' => [
                    'placeholder' => 'GDZ Suez - Luisant (28)'
                ]
            ])
            
            ->add('business', EntityType::class, [
                'label' => 'Entreprise',
                'class' => User::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('u')
                    ->andWhere('u.roles LIKE :role')
                    ->setParameter('role', '%ROLE_BUSINESS%')
                    ->orderBy('u.name', 'ASC');
                },
                'required' => false,
                'empty_data' => null,
                'placeholder' => 'Aucun',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Experience::class,
        ]);
    }
}
