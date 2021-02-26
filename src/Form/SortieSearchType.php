<?php

namespace App\Form;

use App\Entity\Site;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
