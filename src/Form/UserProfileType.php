<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserProfileType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('fname', TextType::class, [
        'attr' => ['class' => 'form-control'],
        'label' => 'First Name'
      ])
      ->add('lname', TextType::class, [
        'attr' => ['class' => 'form-control'],
        'label' => 'Last Name'
      ])

      ->add("imageFile", VichImageType::class, [
        // 'mapped' => false,
        'label' => 'Profile Picture',
        'required' => false,
        'attr' => ['class' => 'form-control'],
        'constraints' => [
          new File([
            'maxSize' => '1024k',
            'mimeTypes' => [
              'image/png',
              'image/jpeg',
              'image/jpg',
            ],
            'mimeTypesMessage' => 'Please upload a valid image file, supported Formats are: .png, .jpeg, .jpg',
          ])
        ],
      ]);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => User::class,
    ]);
  }
}
