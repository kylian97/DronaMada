<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;


class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailerInterface): Response
    {
        $contact = new Contact();

        $form = $this -> createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 

           
            $contact = $form -> getData();
            $entityManager -> persist ($contact);
            $entityManager->flush();

            //Email 

            $email = (new TemplatedEmail())
            ->from($contact->getEmail())
            ->to('admin@dronamada.com')
            ->subject($contact->getSubject())
             // path of the Twig template to render
            ->htmlTemplate('emails/contact.html.twig')

            // pass variables (name => value) to the template
             ->context([
            'contact' => $contact
            ])
            ;

           $mailerInterface->send($email);

            $this->addFlash(
            'success',
            'Votre demandé a été envoyé avec succès'

            );

            return $this->redirectToRoute('app_contact');
            

             // dd($form->getData());
        }
        
        return $this->render('contact/index.html.twig', [

             'form' => $form->createView(),

        ]);
    }
}
