<?php

namespace App\Service;

use App\Repository\CategoryRepository;

class CategoryService
{

    private $category;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->category = $categoryRepository->findAll();
    }

    public function getFullCategories()
    {
        return $categories = $this->category;
    }
}
