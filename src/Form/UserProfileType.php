<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
                'attr' => ['class' => 'form-control mb-2'],
            ])
            ->add('fname', null, [
                'attr' => ['class' => 'form-control mb-2'],
            ])
            ->add('lname', null, [
                'attr' => ['class' => 'form-control mb-2'],
            ]);

        if ($options['is_admin']) {
            $builder->add('roles', ChoiceType::class, [
                'choices' => [
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
                'expanded' => true,
                'label' => 'Roles',
                'choice_attr' => function ($choice, $key, $value) {
                    return ['class' => 'ms-2 me-1'];
                },
                'attr' => ['class' => 'ms-2 me-1'],
            ]);
        }

        $builder->add('password', null, [
            'attr' => ['class' => 'form-control mb-2'],
            'required' => false,
            'mapped' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_admin' => false, // default false
        ]);

        $resolver->setAllowedTypes('is_admin', 'bool');
    }
}
