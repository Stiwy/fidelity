<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Identifiant',
                'label_attr' => [
                    'class' => 'block text-xl',
                ],
                'attr' => [
                    'class' => 'pl-3 bg-gray-100 w-full h-8'
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'mapped' => false,
                'label' => 'Role',
                'choices' => [
                    'Membre' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN'
                ],
                'label_attr' => [
                    'class' => 'block text-xl',
                ],
                'attr' => [
                    'class' => 'pl-3 bg-gray-100 w-full h-8'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'required' => false,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'label_attr' => [
                        'class' => 'block text-xl',
                    ],
                    'attr' => [
                        'class' => 'pl-3 bg-gray-100 w-full h-8'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe',
                    'label_attr' => [
                        'class' => 'block text-xl',
                    ],
                    'attr' => [
                        'class' => 'pl-3 bg-gray-100 w-full h-8'
                    ]
                ],
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
            'data_class' => User::class,
        ]);
    }
}
