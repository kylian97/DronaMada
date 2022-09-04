<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Address;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {    
        // dd($options);
        // débug options, et c'est le user qui va nous intéréssé a la fin 

       $user = $options['user'];
       //déclaration de la variable user, qui prendras la variable option et la clé user

      


        $builder
            ->add('addresses', EntityType::class, [

                'label' => 'Choisissez votre adresse de livraison',
                'required' => true,
                'class' => Address::class,
                'choices' => $user->getAddresses(),
                'multiple' => false,
                'expanded' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
           'user' => array()
        ]);
    }
}
