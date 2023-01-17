<?php

namespace App\Controller;

use App\Repository\DishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    /**
     * @Route("/menu", name="menu")
     */
    public function menu(DishRepository $dr): Response
    {

        $dish = $dr->findAll();


        return $this->render('menu/index.html.twig', [
            'dishes' => $dish
        ]);
    }
}
