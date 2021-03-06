<?php

namespace App\Form;

use App\Entity\Site;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('pseudo', TextType::class,[
            'label' =>'Pseudo',
            'required' => true,
            'trim' => true,
        ]);

        $builder->add('nom', TextType::class,[
            'label' =>'Nom',
            'required' => true,
            'trim' => true,
        ]);

        $builder->add('prenom',TextType::class, [
            'label' => 'Prénom',
            'required' => true,
            'trim' => true
        ]);

        $builder->add('telephone',TelType::class, [
            'label' => 'Téléphone',
            'required' => false,
            'trim' => true
        ]);

        $builder->add('email',EmailType::class, [
            'label' => 'Email',
            'required' => true,
            'trim' => true,
        ]);

        $builder->add('plainPassword',RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Password non conforme !',
            'options' => ['attr' => ['class' => 'password-field']],
            'required' => false,
            'first_options'  => ['label' => 'Password'],
            'second_options' => ['label' => 'Confirmation'],
        ]);

        $builder->add('campus',EntityType::class, [
            'label' => 'Site de rattachement',
            'class' => Site::class,
            'query_builder' => function(EntityRepository $er){
            return $er->createQueryBuilder('site')
                ->orderBy('site.nom', 'ASC');
            },
            'choice_label'=> 'nom',
            'placeholder'=> 'Choisir un campus',
            'required' => true,
            'trim' => true,
        ]);

        $builder->add('image', FileType::class,[
            'label'=>'Ma photo',
            'required'=>false,
            'mapped'=>false,
            'constraints'=> [
        new File([
            'maxSize' => '1024k',
            'mimeTypes' => [
                'image/jpeg',
                'image/png',

            ],
            'mimeTypesMessage' => 'Format accepté JPEG ou PNG',
        ])
    ],

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
