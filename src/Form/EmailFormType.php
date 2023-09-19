<?php

// src/Form/EmailFormType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailFormType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('from', EmailType::class, [
        'attr' => ['class' => 'form-control'],
        'data' => 'symfonydeveloper00@gmail.com',
        // 'disabled' => true
      ])
      ->add('to', EmailType::class, [
        'attr' => ['class' => 'form-control']
      ])
      ->add('subject', TextType::class, [
        'attr' => ['class' => 'form-control']
      ])
      ->add('text', TextareaType::class, [
        'attr' => ['class' => 'form-control']
      ])
      ->add('send', SubmitType::class, [
        'attr' => ['class' => 'btn btn-success my-3']
      ]);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    // Configure form options here if needed
  }
}
