<?php

namespace App\Controller\client\dashboard_client;

use App\Service\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class DashboardClientController extends AbstractController
{

    protected $category;
    private $security;

    public function __construct(CategoryService $categoryService, Security $security)
    {
        $this->category = $categoryService->getFullCategories();
        $this->security = $security;

    }

    /**
     * @Route("/dashboard/client", name="dashboard_client")
     */
    public function index(): Response
    {
        if($this->security->getUser() != null){
            return $this->render('client/dashboard_client/dashboard.html.twig', [
                'controller_name' => 'DashboardClientController',
                'categories' => $this->category,
            ]);
        }else{
            return $this->redirectToRoute('app_login');
        }
    }
}
