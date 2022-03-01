<?php

namespace App\Form;

use App\Entity\Participations;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status',ChoiceType::class, [
                "label"=>false,
                'required'=>false,
                'placeholder'=> 'Dis nous si tu viens !',
                "choices"=>[
                    "Participe"=> 'participe',
                    "Intéressé.e"=> 'interet',
                    "Participe pas" => 'participe pas'
                ]
            ])

            ->add('comment',TextType::class, [
                "label"=>false,
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Un message à nous laisser ?'
                ]
            ])

            ->add('Envoyer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participations::class,
        ]);
    }
}
