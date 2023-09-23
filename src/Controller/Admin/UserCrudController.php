<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            yield IdField::new('id')->hideWhenUpdating()->hideWhenCreating(),
            yield TextField::new('fname')->setLabel('FirstName'),
            yield TextField::new('lname')->setLabel('LastName'),
            yield EmailField::new('email')->setLabel('eMail'),
            yield TextField::new('user_address')->setLabel('Address'),
            // yield TextField::new('user_address')->setLabel('Address'),
            yield ArrayField::new('roles')->setLabel('Role'),
            yield TextField::new('password')->hideWhenUpdating()->hideOnIndex()->setLabel('Password'),

            yield ImageField::new('imageName')
                ->setBasePath('pictures/') // This is where your images are stored for display
                ->setUploadDir('public/pictures/') // This is where uploaded images will be stored
                ->setLabel('Image'), // Optionally, you can set a label for the field

            // TextareaField::new('imageFile')->setFormType(VichImageType::class),
        ];
    }
}
