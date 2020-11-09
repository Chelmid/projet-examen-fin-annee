<?php

namespace App\Controller\Admin;


use App\Entity\Personnalisation;
use App\Entity\PriceImpression;
use App\Entity\ZoneDeMarquage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Order;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
        //return $this->render('admin/adminbase.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Xoopar');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('User', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('Order', 'fa fa-Product', Order::class);
        yield MenuItem::linkToCrud('Category', 'fa fa-category', Category::class);
        yield MenuItem::linkToCrud('Product', 'fa fa-Product', Product::class);
        yield MenuItem::linkToCrud('Zone de marquarge', 'fa fa-Product', ZoneDeMarquage::class);
        yield MenuItem::linkToCrud('Personnalisation', 'fa fa-Product', Personnalisation::class);
        yield MenuItem::linkToCrud("Prix d'impression", 'fa fa-Product', PriceImpression::class);
    }
}
