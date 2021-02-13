<?php

namespace App\Form;

use App\Entity\Film;
use App\Entity\Salle;
use App\Entity\Seance;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('heure', DateTimeType::class,
            [
                'input' => 'datetime_immutable',
            ])
            ->add('idFilm', EntityType::class,
            [
                'class' => Film::class,
                'choice_label' => 'Nom',
                "attr" =>
                [
                    "class" => "form-control"
                ]
            ])
            ->add('idSalle', EntityType::class,
            [
                'class' => Salle::class,
                'choice_label' => 'Numero',
                "attr" =>
                    [
                        "class" => "form-control"
                    ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Seance::class,
        ]);
    }
}
