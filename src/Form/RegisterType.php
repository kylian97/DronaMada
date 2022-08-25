<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TypeTextType::class, [  
                 'label'=> 'Votre Prénom',
                 'constraints' => new Length([
                    'min' => 2,
                    'max' => 30

                 ]),
                'attr' => [
                    'placeholder' => 'Merci de saisir votre Prénom'
                ]
            ]) 
            ->add('lastName', TypeTextType::class, [   
                'label' => 'Votre Nom', 
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 30

                 ]),
                 'attr' => [
                    'placeholder' => 'Merci de saisir votre Nom'
                 ]
            ])
            ->add('email', EmailType::class, [         
                'label' => 'E-mail', 
                'constraints' => new Length([
                    'min' => 4,
                    'max' => 60

                 ]),
                 'attr' => [
                      'placeholder' => 'Merci de saisir votre Email'
                 ]
            ])  

            ->add('password', RepeatedType::class, [  
                
                'type' => PasswordType::class,

                'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques',  
                 
                'required'=> true,

                'first_options'=>[
                                  'label' => 'Mot de passe',
                                        'attr' => [ 
                                                    'placeholder' => 'Merci de saisir votre Mot de passe'
                                                  ]
                
                                ],

                'second_options' => [
                                    'label' => 'Confirmer votre mot de passe',
                                     'attr' => [
                                                 'placeholder' => 'Merci de confirmer votre mot de passe'
                                               ]

                                  ]
            ])
            
            ->add('submit', SubmitType::class, [
                'label' => 'Inscription'
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
