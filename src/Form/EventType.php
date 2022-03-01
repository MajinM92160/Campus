<?php

namespace App\Form;

use App\Entity\Events;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label'=>false,
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Nom de l\'évènement'
                ]
            ])
            ->add('date', DateType::class, [
                'required'=>false,
                'label'=>false,
                'placeholder' => [
                    'year' => '', 'month' => 'Date', 'day' => '',
                ],
                
            ])
            ->add('place', TextType::class, [
                'required'=>false,
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Lieu de l\'évènement'
                ]
            ] )
            ->add('description', TextareaType::class, [
                'required'=>false,
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Description de l\'évènement'
                ]
            ] )
            ->add('free', CheckboxType::class, [
                'label'=>'Évènement gratuit ?',
                'required'=>false,
            ])
            ->add('price', NumberType::class, [
                'label'=>false,
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Montant de la place'
                ]
            ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Events::class,
        ]);
    }
}
