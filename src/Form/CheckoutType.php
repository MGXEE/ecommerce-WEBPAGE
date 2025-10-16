<?php

namespace App\Form;
use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('fullName', TextType::class, [
            'attr' => ['label' => 'Full name', 'class' => 'form-control mb-2']
        ])

        ->add('street', TextType::class, [
            'attr' => ['label' => 'Street', 'class' => 'form-control mb-2']
        ])

        ->add('city', TextType::class, [
            'attr' => ['label' => 'City', 'class' => 'form-control mb-2']
        ])

        ->add('zip', TextType::class, [
            'attr' => ['label' => 'ZIP', 'class' => 'form-control mb-2']
        ])


        ->add('email', TextType::class, [
            'attr' => ['label' => 'Email', 'class' => 'form-control mb-2']
        ])

        ->add('phone', TextType::class, [
            'required' => false,
            'attr' => [ 'label' => 'Phone', 'class' => 'form-control mb-2']
        ])


        ->add('country', CountryType::class, [


            'attr' => ['label' => 'Country', 'class' => 'form-control mb-2']
        ])

        ->add('paymentMethod', ChoiceType::class, [
            'choices' => [
                'Fake Pay' => 'Fake',
                'Cash' => 'Cash',
            ],
            'placeholder' => 'Select method',
            'attr' => [ 'class' => 'form-control mb-2']
            
            
        ])
        ; 

    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults( [
            'data_class' => Order::class,
        ]);
    }
}
