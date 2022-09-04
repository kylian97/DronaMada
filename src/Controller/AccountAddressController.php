<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountAddressController extends AbstractController
{   private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        
    }

    #[Route('/compte/adresses', name: 'app_account_address')]
    public function index(): Response
    {
        //regarder ce qui se passe dans le getUser
       // dd($this->getUser());
       
       
        return $this->render('account/address.html.twig', [
        ]);
    }

    #[Route('/compte/ajouter-une-adresse', name: 'app_account_address_add')]
    public function add(Cart $cart, Request $request): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            //la variable addresse, va être modifier avec le "setUser", et va être récupéré avec le "getUser" (ce qui va pouvoir récupéré le USER déjà connecté)
            $address->setUser($this->getUser()); 

            //fige la donné ,prépare, pour inséré en base de donné (adresse) 
            $this->entityManager->persist($address);

            //envoie de la base de donnée
            $this->entityManager->flush();

            // Si j'ai des produits dans mon panier, a ce moment je veux que tu redirige vers 'order', sinon si j'ai rien a l'intérieur tu me redirige vers 'account_address'
           if ($cart->get()) {

             return $this-> redirectToRoute('app_order');

           }else {
            //redirige a la page mes adresse
            return $this->redirectToRoute('app_account_address');
           }

            
        }

        return $this->render('account/address_form.html.twig', [

            'form'=> $form->createView()
        ]);
    }


    #[Route('/compte/modifier-une-adresse/{id}', name: 'app_account_address_edit')]
    public function edit(Request $request, $id): Response
    {

        //recherche de l'adresse qu'on souhaite modifier 
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        //vérification si mon adresse existe OU si l'adresse que je viens de récupérer est bien l'adresse de l'utilisateur 
        if (!$address || $address->getUser() != $this->getUser()) {
            return $this->redirectToRoute( 'app_account_address');
        }


        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            //la variable addresse, va être modifier avec le "setUser", et va être récupéré avec le "getUser" (ce qui va pouvoir récupéré le USER déjà connecté)
            // $address->setUser($this->getUser()); 

            //fige la donné ,prépare, pour inséré en base de donné (adresse) 
            // $this->entityManager->persist($address);

             //grace a la condition if nous n'avons plus besoin de persit et de set

            //envoie de la base de donnée
            $this->entityManager->flush();
            //redirige a la page mes adresse
            return $this->redirectToRoute('app_account_address');
        }

        return $this->render('account/address_form.html.twig', [

            'form'=> $form->createView()
        ]);
    }

    #[Route('/compte/supprimer-une-adresse/{id}', name: 'app_account_address_delete')]
    public function delete($id): Response
    {

        //recherche de l'adresse qu'on souhaite modifier 
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

       //je veux que l'adresse existe et en plus de sa que JE sois l'utilisateur concerné sinon je n'excuterais pas le contenu de cette suppression d'adresse

        if ($address && $address->getUser() == $this->getUser()) {
           
            //maintenant je la supprime cette adresse 
            $this->entityManager->remove($address);
            //envoie de la base de donnée
            $this->entityManager->flush();
        }

            //redirige a la page mes adresse
            return $this->redirectToRoute('app_account_address');
        }

}
