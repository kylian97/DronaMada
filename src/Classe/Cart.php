<?php

namespace App\Classe;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
//use Symfony\Component\HttpFoundation\Session\SessionInterface;


class Cart 

{
//private $session;
private $requestStack;
private $entityManager;

//public function __construct(SessionInterface $session)
  public function __construct( EntityManagerInterface $entityManager, RequestStack $requestStack)
{
   //$this->session = $session;
   $this->requestStack = $requestStack;
   $this->entityManager = $entityManager;
}


public function add($id)
{
    $session = $this->requestStack->getSession();
    $cart = $session->get('cart', []);

    if (!empty($cart[$id])) {
        $cart[$id]++; 
     } else {
         $cart[$id] = 1;
     }

    $session->set('cart',$cart); 
    
    /* $cart = $this->session->get('cart', []);

    if (!empty($cart[$id])) {
       $cart[$id]++; 
    } else {
        $cart[$id] = 1;
    }

    $this->session->set('cart', $cart); */
}

public function get()
{
    
    return $this->requestStack->getSession()->get('cart');
}

public function remove()
  {
    
    return $this->requestStack->getSession()->remove('cart');
  }

public function delete($id)
  {
    $session = $this->requestStack->getSession();
    $cart = $session->get('cart', []);

    unset($cart[$id]);


    return $session->set('cart',$cart); 
  }




   public function decrease($id){

    $session = $this->requestStack->getSession();
    $cart = $session->get('cart', []);

    if ($cart[$id] > 1) {
      
      $cart[$id]--;  // retirer une quantité

    }else {

      unset($cart[$id]);   //supprimer mon produit 
      
    }
      //vérifier si la quantité de notre produit 

      return $session->set('cart',$cart); 
    
   }

   public function getFull(){ //function qui retourne tout le panier

    $cartComplete = [];

    if ($this->get()) {
        
        foreach ($this->get() as $id => $quantity){
           $product_object = $this->entityManager->getRepository(Product::class)->findOneById($id);

           if(!$product_object){

            $this->delete($id);
            continue;

           }
            $cartComplete[] = [
                'product' => $product_object,
                'quantity' => $quantity
            ];
        }
    }

    return $cartComplete;

   }
   
}