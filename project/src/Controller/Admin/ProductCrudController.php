<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use Symfony\Component\HttpFoundation\File\File;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            Field::new('name'),
            Field::new('sku'),
            Field::new('price'),
            ImageField::new('image'),
            DateField::new('create_at'),
            DateField::new('updated_at'),
            Field::new('description'),
            Field::new('quantity'),
        ];
    }

}
