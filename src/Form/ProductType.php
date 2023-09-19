<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Status;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'attr' => ['class' => 'form-control test']
            ])
            ->add('description', null, [
                'attr' => ['class' => 'form-control']
            ])

            ->add('price', null, [
                'attr' => ['class' => 'form-control']
            ])
            // ->add('picture', null, [
            //     'attr' => ['class' => 'form-control'],
            //     'required' => false
            // ])
            ->add('date', null, [
                'attr' => ['class' => 'form-control'],
                'widget' => 'single_text'
            ])
            ->add('categorie', null, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('fkStatus', EntityType::class, [
                'class' => Status::class,
                'choice_label' => 'status',
                'attr' => ['class' => 'form-control'],
                'label' => 'Status'
            ])
            // ->add('pictureUrl', FileType::class, [
            //     'label' => 'Upload Picture',
            //     'mapped' => false,
            //     'required' => false,
            //     'attr' => ['class' => 'form-control'],
            //     'constraints' => [
            //         new File([
            //             'maxSize' => '1024k',
            //             'mimeTypes' => [
            //                 'image/png',
            //                 'image/jpeg',
            //                 'image/jpg',
            //             ],
            //             'mimeTypesMessage' => 'Please upload a valid image file, supported Formats are: .png, .jpeg, .jpg',
            //         ])
            //     ],
            // ])

            ->add("imageFile", VichImageType::class, [
                // 'mapped' => false,
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

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
