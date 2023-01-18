<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerController extends AbstractController
{
    /**
     * @Route("/mail", name="mail")
     */
    public function sendEmail(MailerInterface $mailer, Request $request): Response
    {

        $emailForm = $this->createFormBuilder()
                        ->add('message', TextareaType::class, [
                            'attr' => array('row' => '5')
                        ])
                        ->add('Submit', SubmitType::class)
                        ->getForm();

        $emailForm->handleRequest($request);

        if($emailForm->isSubmitted()){

            $input = $emailForm->getData();
            $text = ($input['message']);

            $table = 'table1';

            $email = ( new TemplatedEmail())
                    ->from('yashp.albiorix@gmail.com')
                    ->to('hirenm.albiorix@gmail.com')
                    ->subject('Order')
                    ->htmlTemplate('mailer/mail.html.twig')
                    ->context([
                        'table' => $table,
                        'text' => $text 
                    ])
                    ;

                    $mailer->send($email);

                    $this->addFlash('message','Message sent succesfully');

                    return $this->redirect($this->generateUrl('mail'));
                }

                return $this->render('mailer/index.html.twig',[
                    'emailForm' => $emailForm->createView() 
                ]);
            
            }
}
