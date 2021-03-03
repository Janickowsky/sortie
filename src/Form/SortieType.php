<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Blank;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom', TextType::class, [
            'label' => 'Nom de la sortie',
            'trim' => true,
            'required' => true,
        ]);

        $builder->add('dateHeureDebut', DateType::class, [
            'label' => 'Date et heure de la sortie',
            'trim' => true,
            'required' => true,
            'widget' => 'single_text',
        ]);

        $builder->add('dateLimiteInscription', DateType::class, [
            'label' => 'Date limite d\'inscription',
            'trim' => true,
            'required' => true,
            'widget' => 'single_text',
        ]);

        $builder->add('nbInscriptionMax', NumberType::class, [
            'label' => 'Nombre de places',
            'trim' => true,
            'required' => true,
        ]);

        $builder->add('duree', IntegerType::class, [
            'label' => 'Duree (en minutes)',
            'trim' => true,
            'required' => true,
        ]);

        $builder->add('infosSortie', TextareaType::class, [
            'label' => 'Description et infos',
            'trim' => true,
            'required' => true,
        ]);

        $builder->add('ville', EntityType::class, [
            'label' => 'Ville',
            'trim' => true,
            'mapped' => false,
            'required' => true,
            'class' => Ville::class,
            'choice_label' => 'nom',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('ville')
                    ->orderBy('ville.nom', 'ASC');
            },
            'multiple' => false,
            'placeholder' => 'Choisir une ville',
        ]);


        $builder->add('lieu', EntityType::class, [
            'label' => 'Lieu',
            'trim' => true,
            'required' => true,
            'class' => Lieu::class,
            'choice_label' => 'nom',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('lieu')
                    ->orderBy('lieu.nom', 'ASC');
            },
            'multiple' => false,
            'placeholder' => 'Choisir un lieu',
        ]);
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            $sortie = $event->getData();
            $form = $event->getForm();
            if($sortie->getLieu() != null){
                $form->get('ville')->setData($sortie->getLieu()->getVille());
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
