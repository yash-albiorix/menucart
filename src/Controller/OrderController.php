<?php

namespace App\Controller;

use App\Entity\Dish;
use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;

class OrderController extends AbstractController
{
    /**
    * @var Security
    */
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

    /**
     * @Route("/orders", name="orders")
     */
    public function index(OrderRepository $or, SessionInterface $session): Response
    {
        $user = $this->security->getUser();

        if(!is_null($user)){
             $orders = $or->findAll();
        }else{
            $tableNo = $session->get('tableNo');
            $tableNumber = 'table' . $tableNo;

            $orders = $or->findBy(
                ['_table' => $tableNumber]
            );
        }
        

        return $this->render('order/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    /**
     * @Route("/addorder/{id}", name="addorder")
     */
    public function addOrder(Dish $dish, SessionInterface $session){

        $order = new Order();

        $tableNo = $session->get('tableNo');
        $tableNumber = 'table' . $tableNo;

        if(is_null($tableNo)){
            $tableNumber = 'table1';    
            $session->set('tableNo', '1');
        }


        $order->setTable($tableNumber);
        $order->setName($dish->getName());
        $order->setOrderNo($dish->getId());
        $order->setPrice($dish->getPrice());
        $order->setStatus('active');

        //Entity Manager
        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();


        $this->addFlash('Order', $order->getName() . ' is added to order');

        return $this->redirect($this->generateUrl('menu'));
    }

    /**
     * @Route("/status/{id},{status}", name="status")
     */

    public function status($id, $status){
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository(Order::class)->find($id);

        $order->setStatus($status);
        $em->flush();

        return $this->redirect($this->generateUrl('orders'));
    }

      /**
     * @Route("/removeOrder/{id}", name="removeOrder")
     */
    public function remove($id, OrderRepository $or){
        //Entity Manager 
        $em = $this->getDoctrine()->getManager();
        $order = $or->find($id);
        $em->remove($order);
        $em->flush();

        return $this->redirect($this->generateUrl('orders'));

    }
}
