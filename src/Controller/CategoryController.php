<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index()
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }


    //     /**
    //  * @Route("/category/{libelle}", name="category"_list)
    //  */
    // public function list($libelle, CategoryRepository $repo)
    // {
        


    //     return $this->render('category/index.html.twig', );
    // }
}
