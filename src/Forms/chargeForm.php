<?php

namespace App\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class chargeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $builder
        ->add('entityId', TextType::class)
        ->add('paymentBrand', TextType::class)
        ->add('paymentType', TextType::class)
        ->add('amount', TextType::class, [
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter your amount',
                ]),
            ],
            'attr' => array(
                'placeholder' => ' Your Otp',
                'class' => 'form-control'
            ),
            'label' => false
        ])
        ->add('currency', TextType::class, [
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter your currency',
                ]),
            ],
            'attr' => array(
                'placeholder' => ' Your currency',
                'class' => 'form-control'
            ),
            'label' => false
        ])
        ->add('description', TextType::class, [
            'attr' => array(
                'placeholder' => ' Your description',
                'class' => 'form-control'
            ),
            'label' => false
        ])
        ->add('cardNumber', TextType::class, [
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter your card number',
                ]),
            ],
            'attr' => array(
                'placeholder' => ' Your currency',
                'class' => 'form-control'
            ),
            'label' => false
        ])
        ->add('cardExpiryMonth', TextType::class, [
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter your card expiry month',
                ]),
            ],
            'attr' => array(
                'placeholder' => ' Your card expiry month',
                'class' => 'form-control'
            ),
            'label' => false
        ])
        ->add('cardHolder', TextType::class)
        ->add('cardExpiryYear', TextType::class, [
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter your card expiry year',
                ]),
            ],
            'attr' => array(
                'placeholder' => ' Your card expiry year',
                'class' => 'form-control'
            ),
            'label' => false
        ])
        
        ->add('cvv', TextType::class, [
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter your card cvv',
                ]),
            ],
            'attr' => array(
                'placeholder' => ' Your card cvv',
                'class' => 'form-control'
            ),
            'label' => false
        ]);
    }


    public function configureOptions(OptionsResolver $resolver):void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'allow_extra_fields' => true
        ]);
    
    }

}
