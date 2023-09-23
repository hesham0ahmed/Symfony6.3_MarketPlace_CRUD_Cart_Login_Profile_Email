<?php

namespace App\Controller\Admin;

use App\Entity\ProductInCart;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductInCartCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductInCart::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // yield IdField::new('id')->hideWhenUpdating()->hideWhenCreating(),
            yield IdField::new('id')->setLabel('id'),
            yield IdField::new('fkUserId')->setLabel('User id'),
            yield IdField::new('fkProductId')->setLabel('Product id'),

            yield IdField::new('price')->setLabel('Price in $'),


        ];
    }
    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
