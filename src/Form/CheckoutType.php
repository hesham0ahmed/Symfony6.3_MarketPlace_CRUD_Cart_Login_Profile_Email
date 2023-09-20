<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'First Name'
            ])
            ->add('lastName', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Last Name'
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Email'
            ])
            ->add('address', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Address'
            ])
            ->add('country', ChoiceType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Country'
            ])
            ->add('state', ChoiceType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'State'
            ])
            ->add('zip', IntegerType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Zip'
            ])
            ->add('save-info', CheckboxType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Save Info'
            ])
            ->add('paymentmethod', RadioType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Payment Type'
            ])
            ->add('save', SubmitType::class, ['label' => 'Submit']);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserData::class, // Create a UserData class to hold the form data
        ]);
    }
}
