<?php

namespace App\Controller\Admin;

use App\Entity\PriceImpression;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PriceImpressionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PriceImpression::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('type'),
            NumberField::new('price'),
        ];
    }
}
