<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom', TextType::class, [
            'label' => 'Nom de la sortie:',
            'trim' => true,
            'required' => true,
        ]);

        $builder->add('dateHeureDebut', DateTimeType::class, [
            'label' => 'Date et heure de la sortie:',
            'trim' => true,
            'required' => true,
        ]);

        $builder->add('dateLimiteInscription', DateTimeType::class, [
            'label' => 'Date limite d\'inscription:',
            'trim' => true,
            'required' => true,
        ]);

        $builder->add('nbInscriptionMax', IntegerType::class, [
            'label' => 'Nombre de places:',
            'trim' => true,
            'required' => true,
        ]);



    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
