<?php

namespace App\Form;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        if ($options['student'] == true) :
    
        $builder
            ->add('email', EmailType::class,[
                'required'=>false,
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Mon email'
                ]
            ])
            ->add('password', PasswordType::class, [
                'required'=>false,
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Mon mot de passe'
                ]
            ])
            ->add('confirmPassword', PasswordType::class,[
                'required'=>false,
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Confirmer mon mot de passe'
                ]
            ])
            ->add('firstName',TextType::class,[
                'required'=>false,
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Mon prénom'
                ]
            ])
            ->add('lastName',TextType::class,[
                'required'=>false,
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Mon nom'
                ]
            ])
            ->add('phone',TextType::class,[
                'required'=>false,
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Mon numéro de téléphone'
                ]
            ])
            ->add('address',TextType::class,[
                'required'=>false,
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Mon adresse'
                ]
            ])
            ->add('enregistrer', SubmitType::class) ;

        elseif ($options['office'] == true) :
        
            $builder
                ->add('email', EmailType::class,[
                    'required'=>false,
                    'label'=>false,
                    'attr'=>[
                        'placeholder'=>'Mon email'
                    ]
                ])
                ->add('password', PasswordType::class, [
                    'required'=>false,
                    'label'=>false,
                    'attr'=>[
                        'placeholder'=>'Mon mot de passe'
                    ]
                ])
                ->add('confirmPassword', PasswordType::class,[
                    'required'=>false,
                    'label'=>false,
                    'attr'=>[
                        'placeholder'=>'Confirmer mon mot de passe'
                    ]
                ])
                ->add('name',TextType::class,[
                    'required'=>false,
                    'label'=>false,
                    'attr'=>[
                        'placeholder'=>'Mon nom'
                    ]
                ])
                ->add('presentation',TextType::class,[
                    'required'=>false,
                    'label'=>false,
                    'attr'=>[
                        'placeholder'=>'Présentation de mon BDE'
                    ]
                ])
                ->add('phone',TextType::class,[
                    'required'=>false,
                    'label'=>false,
                    'attr'=>[
                        'placeholder'=>'Mon numéro de téléphone'
                    ]
                ])
                ->add('address',TextType::class,[
                    'required'=>false,
                    'label'=>false,
                    'attr'=>[
                        'placeholder'=>'Mon adresse'
                    ]
                ])
                ->add('code_office',TextType::class,[
                    'required'=>false,
                    'label'=>false,
                    'attr'=>[
                        'placeholder'=>'Code secret de mon BDE*',
                    ]
                ])
                
                ->add('enregistrer', SubmitType::class)
            ;

            elseif ($options['editstudent'] == true) :

                        $builder
                        ->add('email', EmailType::class,[
                            'required'=>false,
                            'label'=>false,
                            'attr'=>[
                                'placeholder'=>'Mon email'
                            ]
                        ])
                    
                        ->add('firstName',TextType::class,[
                            'required'=>false,
                            'label'=>false,
                            'attr'=>[
                                'placeholder'=>'Mon prénom'
                            ]
                        ])
                        ->add('lastName',TextType::class,[
                            'required'=>false,
                            'label'=>false,
                            'attr'=>[
                                'placeholder'=>'Mon nom'
                            ]
                        ])
                        ->add('phone',TextType::class,[
                            'required'=>false,
                            'label'=>false,
                            'attr'=>[
                                'placeholder'=>'Mon numéro de téléphone'
                            ]
                        ])
                        ->add('address',TextType::class,[
                            'required'=>false,
                            'label'=>false,
                            'attr'=>[
                                'placeholder'=>'Mon adresse'
                            ]
                        ])
                        ->add('enregistrer', SubmitType::class) ;


        endif;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
            'student' => false,
            'office' => false,
            'editstudent'=>false,
        ]);
    }

}
