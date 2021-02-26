<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Ville;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


            $builder->add('nom', TextType::class,[
                'label' =>'Nom :',
                'required' => true,
                'trim' => true,
            ]);

            $builder->add('rue', TextType::class,[
                'label' =>'Rue :',
                'required' => true,
                'trim' => true,
            ]);

            $builder->add('latitude', NumberType::class,[
                'label' =>'Latitude :',
                'required' => true,
                'trim' => true,
            ]);

            $builder->add('longitude', NumberType::class,[
                'label' =>'Longitude :',
                'required' => true,
                'trim' => true,
            ]);

            $builder->add('nom',EntityType::class, [
                'label' => 'Ville :',
                'class' => Ville::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('ville')
                        ->orderBy('ville.nom', 'ASC');
                },
                'choice_label'=> 'nom',
                'placeholder'=> 'Choisir une ville',
                'required' => true,
                'trim' => true,
            ]);

            $builder->add ('submit', SubmitType::class, [
                'label' => 'Enregistrer',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
