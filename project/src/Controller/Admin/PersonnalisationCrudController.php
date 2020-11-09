<?php

namespace App\Controller\Admin;

use App\Entity\Personnalisation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PersonnalisationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Personnalisation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('priceImpression'),
            TextField::new('file'),
            IntegerField::new('top_position'),
            IntegerField::new('left_position'),
            IntegerField::new('height'),
            IntegerField::new('width'),
            TextField::new('datauri'),
            TextField::new('impression'),
        ];
    }

}
