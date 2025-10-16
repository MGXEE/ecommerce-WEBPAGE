<?php

namespace App\Form;

use App\Entity\Products;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ProductsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', null, [
                'attr' => ['class' => 'form-control mb-2']
            ])
            ->add('product', null, [
                'attr' => ['class' => 'form-control mb-2']
            ])
            ->add('price', null, [
                'attr' => ['class' => 'form-control mb-2']
            ])
            ->add('description', null, [
                'attr' => ['class' => 'form-control mb-2']
            ])
            ->add('category', null, [
                'attr' => ['class' => 'form-control mb-2']
            ])
            ->add('visibility', CheckboxType::class, [
                'required' => false,
                'label' => 'Visible to public',
            ])

            // form for discount
            ->add('discount', null, [
                'required' => false,
                'attr' => ['class' => 'form-control mb-2'],
                'label' => 'Discount (%)'
            ])
            ->add('specialOffer', CheckboxType::class, [
                'required' => false,
                'label' => 'Special Offer',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
