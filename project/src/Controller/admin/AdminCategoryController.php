<?php

namespace App\Controller\admin;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class AdminCategoryController extends AbstractController
{
    private $twig;


    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }
    /**
     * @Route("/admin/show/category/add", name="admin_Show_category_add")
     */
    public function addCategory(Request $request)
    {

        $category = new Category();
        $category->setName($request->request->get('category_add'));

        //appelle doctrine
        $en = $this->getDoctrine()->getManager();

        $en->persist($category);
        //push dans la bdd
        $en->flush();

        $this->addFlash('successCategoryAdd', 'La category est bien ajouter');
        return $this->redirectToRoute('admin_show_category');
    }

    /**
     * @Route("/admin/Show/category", name="admin_show_category")
     */
    public function index(Request $request){

        return new Response($this->twig->render('admin_show_category/show.html.twig'), 200);
    }
}
