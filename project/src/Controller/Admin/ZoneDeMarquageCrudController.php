<?php

namespace App\Controller\Admin;

use App\Entity\ZoneDeMarquage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class ZoneDeMarquageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ZoneDeMarquage::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('productId'),
            IntegerField::new('topSpace'),
            IntegerField::new('leftSpace'),
            IntegerField::new('height'),
            IntegerField::new('width'),
        ];
    }

}
