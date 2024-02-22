<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numCommande',TextType::class,[
                'label'=>'N° Commande:',
                'label_attr'=>['class'=>'lab30'],
                'attr'=>['class'=>'form-control w40 my-2', 'autocomplete'=>'off']
            ])
            ->add('dateCommande',DateType::class,[
                'label'=>'Date commande:',
                'label_attr'=>['class'=>'lab30'],
                'attr'=>['class'=>'form-control w50 my-2']
            ])
            ->add('client', EntityType::class, [
                'label'=>'Client:',
                'label_attr'=>['class'=>'lab30'],
                'attr'=>['class'=>'form-select w70 my-2'],
                'class' => Client::class,
                'choice_label' => 'nomClient',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
