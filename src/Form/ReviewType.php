<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('author', TextType::class, [
                'label' => 'Author',
                'required' => true,
                'attr' => ['class' => 'form-control mb-2']
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Review',
                'required' => true,
                'attr' => ['class' => 'form-control mb-2']
            ])
            ->add('rating', IntegerType::class, [
                'label' => 'Rating (1-5)',
                'attr' => ['min' => 1, 'max' => 5],
                'required' => true,
                'attr' => ['class' => 'form-control mb-2']
            ]);
        // 'product_id' und 'created_at' werden im Controller gesetzt, nicht im Formular
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
