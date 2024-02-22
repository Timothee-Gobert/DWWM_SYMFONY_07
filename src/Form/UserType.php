<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username',TextType::class,[
                'label'=>'Nom d\'utilisateur:',
                'label_attr'=>['class'=>'lab30'],
                'attr'=>['class'=>'form-control w40 my-2', 'autocomplete'=>'off']
            ])
            //->add('roles') // a travailler specialement en user controller
            ->add('plainPassword',PasswordType::class,[ // un inpute fictif
                'label'=>'Mot de passe:',
                'label_attr'=>['class'=>'lab30'],
                'attr'=>['class'=>'form-control w40 my-2', 'autocomplete'=>'off', 'placeholder'=>"Ne rien saisir pour garder l'ancienne valeur"],
                'mapped'=>false, // pour empecher symfony de l'enregistrer avec persist et flush
                'required'=>false, // pour que la saisie ne sois pas obligatoire
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
