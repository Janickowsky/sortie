<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {



        $builder->add('nom', TextType::class,[
            'label' =>'Nom :',
            'required' => true,
            'trim' => true,

        ]);
        $builder->add('prenom',TextType::class, [
            'label' => 'Prénom :',
            'required' => true,
            'trim' => true
        ]);

        $builder->add('telephone',TelType::class, [
            'label' => 'Téléphone :',
            'required' => false,
            'trim' => true
        ]);

        $builder->add('email',TextType::class, [
            'label' => 'Email:',
            'required' => true,
            'trim' => true,
        ]);

        $builder->add('password',RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Password non conforme !',
            'options' => ['attr' => ['class' => 'password-field']],
            'required' => true,
            'first_options'  => ['label' => 'Password'],
            'second_options' => ['label' => 'Confirmation'],
        ]);



        $builder->add ('submit', SubmitType::class, [
            'label' => 'Enregistrer',
        ]);

    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
