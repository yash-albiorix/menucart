<?php

namespace App\Controller;

use App\Entity\Dish;
use App\Form\DishType;
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

        $form = $this->createForm(DishType::class, $dish);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            //Entity Manager 
            $em = $this->getDoctrine()->getManager();

            $image = $request->files->get('dish')['attachment'];

            if($image){
                $dateiname = md5(uniqid()) . '.' . $image->guessClientExtension();
            }

            $image->move(
                $this->getParameter('images_folder'),
                $dateiname
            );

            $dish->setImage($dateiname);

            $em->persist($dish);
            $em->flush();

            return $this->redirect($this->generateUrl('dish.edit'));
        }

        //Response
        return $this->render('dishes/create.html.twig', [
            'createForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/update/{id}", name="update")
     */
    public function update(Request $request, $id): Response
    {
        $dish = new Dish();

        $form = $this->createForm(DishType::class, $dish);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            //Entity Manager 
            $em = $this->getDoctrine()->getManager();

            $image = $request->files->get('dish')['attachment'];

            if($image){
                $dateiname = md5(uniqid()) . '.' . $image->guessClientExtension();
            }

            $image->move(
                $this->getParameter('images_folder'),
                $dateiname
            );

            $dish->setImage($dateiname);

            $em->persist($dish);
            $em->flush();

            return $this->redirect($this->generateUrl('dish.edit'));
        }

        //Response
        return $this->render('dishes/create.html.twig', [
            'createForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove($id, DishRepository $dr){
        //Entity Manager 
        $em = $this->getDoctrine()->getManager();
        $dish = $dr->find($id);
        $em->remove($dish);
        $em->flush();

        //Flash Message
        $this->addFlash('success', 'Deleted Succesfully');

        return $this->redirect($this->generateUrl('dish.edit'));

    }

    /**
     * @Route("/show/{id}", name="show")
     */
    public function show(Dish $dish){
        //Response
        return $this->render('dishes/show.html.twig', [
            'dish' => $dish
        ]);
    }
}
