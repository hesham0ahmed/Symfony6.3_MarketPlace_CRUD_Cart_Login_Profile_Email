<?php



namespace App\Controller\Admin;

use App\Entity\Status;
use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            // yield IdField::new('id')->hideWhenUpdating()->hideWhenCreating(),
            yield TextField::new('name'),
            yield TextField::new('description'),
            yield TextField::new('categorie'),
            yield IdField::new('price')->setLabel('Price in $'),
            yield DateField::new('date')->setLabel('Date Create'),
            // yield ChoiceField::new('fk_status')->allowMultipleChoices(),
            // yield IdField::new('fk_status_id'),
            yield AssociationField::new('fk_status')
                ->setFormType(EntityType::class)
                ->setLabel('Status')
                ->setFormTypeOptions([
                    'class' => Status::class, // Specify the target entity class
                    'choice_label' => 'Status',
                    // Specify the field to display in the dropdown (e.g., 'name' or any other field you prefer)
                ]),

            yield ImageField::new('imageName')
                ->setBasePath('pictures/') // This is where your images are stored for display
                ->setUploadDir('public/pictures/') // This is where uploaded images will be stored
                ->setLabel('Image'), // Optionally, you can set a label for the field

            // TextareaField::new('imageFile')->setFormType(VichImageType::class),

        ];
    }
}
