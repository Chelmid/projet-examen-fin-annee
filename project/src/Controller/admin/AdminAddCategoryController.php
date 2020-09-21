<?php

namespace App\Controller\admin;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminAddCategoryController extends AbstractController
{
    /**
     * @Route("/admin/add/category", name="admin_add_category")
     */
    public function index(Request $request)
    {
        $category = new Category();
        $category->setName('bons plans');

        $en = $this->getDoctrine()->getManager();

        $en->persist($category);
        //$en->flush();
        return new Response("Category bien ajouter");
    }
}
