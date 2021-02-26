<?php

namespace App\Form;

use App\Entity\Site;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options){

        $builder->add('site', EntityType::class, [
            'label' => 'Site:',
            'trim' => true,
            'required' => false,
            'class' => Site::class,
            'choice_label' => 'nom',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('site')
                    ->orderBy('site.nom', 'ASC');
            },
            'multiple' => false,
            'placeholder' => 'Choisir un site',
        ]);

        $builder->add('nomSortie', SearchType::class, [
            'label' => 'Le nom de la sortie contient:',
            'trim' => true,
            'required' => false,
        ]);

        $builder->add('orgaTri', CheckboxType ::class, [
            'label' => "Sortie dont je suis l'organisateur/trice",
            'required' => false,
        ]);

        $builder->add('inscritTri', CheckboxType ::class, [
            'label' => "Sortie auxquelles je suis inscrit/e",
            'required' => false,
        ]);

        $builder->add('nonInscritTri', CheckboxType ::class, [
            'label' => "Sortie auxquelles je ne suis pas inscrit/e",
            'required' => false,
        ]);

        $builder->add('passeTri', CheckboxType ::class, [
            'label' => "Sortie passées",
            'required' => false,
        ]);

        $builder->add ('submit', SubmitType::class, [
            'label' => 'Rechercher',
        ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'mapped' =>false,
        ]);
    }
}
