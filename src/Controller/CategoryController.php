<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
     * @Route("/category", name="category.")
     */

class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(CategoryRepository $cr): Response
    {
        $getCat = $cr->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $getCat
        ]);
    }

     /**
     * @Route("/create", name="create")
     */
    public function create(Request $request): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            //Entity Manager 
            $em = $this->getDoctrine()->getManager();

            $em->persist($category);
            $em->flush();

            return $this->redirect($this->generateUrl('category.index'));
        }

        //Response
        return $this->render('category/create.html.twig', [
            'createForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/update/{id}", name="update")
     */
    public function update(Request $request, $id): Response
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            //Entity Manager 
            $em = $this->getDoctrine()->getManager();

            $em->persist($category);
            $em->flush();

            return $this->redirect($this->generateUrl('category.index'));
        }

        //Response
        return $this->render('category/create.html.twig', [
            'createForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove($id, CategoryRepository $cr){
        //Entity Manager 
        $em = $this->getDoctrine()->getManager();
        $category = $cr->find($id);
        $em->remove($category);
        $em->flush();

        //Flash Message
        $this->addFlash('success', 'Deleted Succesfully');

        return $this->redirect($this->generateUrl('category.index'));

    }

}
