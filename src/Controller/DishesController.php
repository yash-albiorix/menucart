<?php

namespace App\Controller;

use App\Entity\Dish;
use App\Repository\DishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
     * @Route("/dish", name="dish.")
     */

class DishesController extends AbstractController
{
    /**
    * @Route("/", name="edit")
    */

    public function index(DishRepository $dishRepository): Response
    {
        $getDishes = $dishRepository->findAll();
        return $this->render('dishes/index.html.twig', [
            'getDishes' => $getDishes
        ]);
    }

     /**
     * @Route("/create", name="create")
     */
    public function create(Request $request): Response
    {
        $dish = new Dish();
        $dish->setName('Pizza');

        //Entity Manager 
        $em = $this->getDoctrine()->getManager();
        $em->persist($dish);
        $em->flush();

        //Response
        return new Response("Dish has been created");
    }
}
