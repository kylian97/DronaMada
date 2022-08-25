<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this -> entityManager = $entityManager;
    }

    #[Route('/inscription', name: 'register')]
    //Injection de dépendance 

    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {

        $user = New User(); //J'aurais un nouveau utilisateur

        $form = $this->createForm(RegisterType::class, $user); // Création du formulaire
        
        //ecoute la requête 
        $form = $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 

            //On ajoute à notre instance(new User)user les données du formulaire

            $user = $form->getData();

            $password = $passwordHasher->hashPassword($user, $user->getPassword());
           // dd($password);
            
           //Définit le nouveau mot de passe crypté
           $user->setPassword($password);

            //fige la data pour l'enregistrer
            $this->entityManager->persist($user);

            //exécute 
            $this->entityManager->flush();
           
        }

        return $this->render('register/index.html.twig', [

            'form' => $form -> createView()

        ]);
    }
}
