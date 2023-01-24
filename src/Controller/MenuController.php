<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\DishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


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

    /**
     * @Route("/selectTable", name="selectTable")
     */
    public function selectTable(Request $request, SessionInterface $session)
    {
        $tableNo = $request->query->get('table');
        $session->set('tableNo', $tableNo);
        return $this->redirectToRoute('orders');
    }
}
