<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom ou établissement',
                'label_attr' => [
                    'class' => 'block text-xl',
                ],
                'attr' => [
                    'class' => 'pl-3 bg-gray-100 w-full h-8'
                ]
            ])
            ->add('phone', NumberType::class, [
                'required' => false,
                'label' => 'Téléphone',
                'label_attr' => [
                    'class' => 'block text-xl',
                ],
                'attr' => [
                    'class' => 'pl-3 bg-gray-100 w-full h-8'
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => false,
                'label' => 'E-mail (facultatif si téléphone rensigné)',
                'label_attr' => [
                    'class' => 'block text-xl',
                ],
                'attr' => [
                    'class' => 'pl-3 bg-gray-100 w-full h-8'
                ]
            ])
            ->add('id', HiddenType::class, [
                'mapped' => false,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
