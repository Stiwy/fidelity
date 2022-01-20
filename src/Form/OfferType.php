<?php

namespace App\Form;

use App\Entity\Offer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => false,
                'label' => 'Titre de l\'offre',
                'label_attr' => [
                    'class' => 'block text-lg',
                ],
                'attr' => [
                    'class' => 'pl-3 bg-gray-100 w-full h-8'
                ]
            ])
            ->add('notified_on', DateTimeType::class, [
                'required' => false,
                'widget' => 'single_text',
                'label' => 'Notifier le client le',
                'label_attr' => [
                    'class' => 'block text-lg',
                ],
                'attr' => [
                    'class' => 'pl-3 bg-gray-100 w-full h-8'
                ]
            ])
            ->add('notified_after_store', IntegerType::class, [
                'required' => false,
                'label' => '',
                'data' => 0,
                'help' => 'Jour(s) avant de notifier le client de l’offre après passage au magasin',
                'help_attr' => [
                    'class' => 'text-sm',
                ],
                'label_attr' => [
                    'class' => 'block text-lg hidden',
                ],
                'attr' => [
                    'class' => 'pl-3 bg-gray-100 w-full'
                ]
            ])
            ->add('new_customer', CheckboxType::class, [
                'required' => false,
                'label' => 'Attribuer aux nouveaux clients',
                'label_attr' => [
                    'class' => 'form-check-label inline-block text-gray-800',
                ],
                'attr' => [
                    'role' => 'switch',
                    'class' => 'form-check-input appearance-none w-9 -ml-10 rounded-full float-left h-5 align-top bg-white bg-no-repeat bg-contain bg-gray-300 focus:outline-none cursor-pointer shadow-sm'
                ]
            ])
            ->add('all_customer', CheckboxType::class, [
                'required' => false,
                'label' => 'Attribuer à tous les clients',
                'label_attr' => [
                    'class' => 'form-check-label inline-block text-gray-800',
                ],
                'attr' => [
                    'role' => 'switch',
                    'class' => 'form-check-input appearance-none w-9 -ml-10 rounded-full float-left h-5 align-top bg-white bg-no-repeat bg-contain bg-gray-300 focus:outline-none cursor-pointer shadow-sm'
                ]
            ])
            ->add('after_visit_store', CheckboxType::class, [
                'required' => false,
                'label' => 'Aprés chaque achat en boutique',
                'label_attr' => [
                    'class' => 'form-check-label inline-block text-gray-800',
                ],
                'attr' => [
                    'role' => 'switch',
                    'class' => 'form-check-input appearance-none w-9 -ml-10 rounded-full float-left h-5 align-top bg-white bg-no-repeat bg-contain bg-gray-300 focus:outline-none cursor-pointer shadow-sm'
                ]
            ])
            ->add('start_offer_on', DateType::class, [
                'required' => false,
                'label' => 'Commencer l’offre le',
                'widget' => 'single_text',
                'label_attr' => [
                    'class' => 'block text-lg',
                ],
                'attr' => [
                    'class' => 'pl-3 bg-gray-100 w-full h-8'
                ]
            ])
            ->add('end_offer_on', DateType::class, [
                'required' => false,
                'label' => 'Finir l’offre le',
                'widget' => 'single_text',
                'label_attr' => [
                    'class' => 'block text-lg',
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
            'data_class' => Offer::class,
        ]);
    }
}
