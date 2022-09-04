<?php

namespace App\Controller;

use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/commande', name: 'app_order')]
    public function index(): Response
    {

        // dd($this->getUser()->getAddresses()->getValues());
        //récupéré les datas avec getValues; 

        //si tu n'as pas d'adresse, alors que je te retourne la page de création d'adresse sinon tu peu continuer ton formulaire 
        if (!$this->getUser()->getAddresses()->getValues()) {
          
            return $this->redirectToRoute('app_account_address_add');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        return $this->render('order/index.html.twig', [

            'form' => $form-> createView()
        ]);
    }
}
