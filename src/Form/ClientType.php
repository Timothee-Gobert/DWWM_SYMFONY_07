<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numClient',TextType::class,[
                'label'=>"CODE:",
                'label_attr'=>["class"=>"lab30 obligatoire"],
                'attr'=>["class"=>"w50 form-control my-4"],
                "required"=>true,
            ])
            ->add('nomClient',TextType::class,[
                'label'=>"NOM:",
                'label_attr'=>["class"=>"lab30"],
                'attr'=>["class"=>"w70 form-control my-4"],
            ])
            ->add('adresseClient',TextType::class,[
                'label'=>"ADRESSE:",
                'label_attr'=>["class"=>"lab30"],
                'attr'=>["class"=>"w70 form-control my-4"],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
