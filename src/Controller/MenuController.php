<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\DishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    /**
     * @Route("/menu", name="menu")
     */
    public function menu(DishRepository $dr, CategoryRepository $cr): Response
    {

        $dish = $dr->findAll();
        $category = $cr->findAll();

        return $this->render('menu/index.html.twig', [
            'dishes' => $dish,
            'categories' => $category
        ]);
    }
}
