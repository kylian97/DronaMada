<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contact = new Contact();

        if ($this->getUser()) {
            $contact->setFirstName($this->getUser()->getFirstName())
            ->setLastName($this->getUser()->getLastName())
            ->setEmail($this->getUser()->getEmail());
        }

        $form = $this -> createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 

           
            $contact = $form -> getData();

            $entityManager -> persist ($contact);

            $entityManager->flush();

            $this->addFlash(
            'success',
            'Votre demandé a été envoyé avec succès'

            );
            

             // dd($form->getData());
        }
        
        return $this->render('contact/index.html.twig', [

             'form' => $form->createView(),

        ]);
    }
}
