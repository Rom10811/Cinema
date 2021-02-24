<?php

namespace App\Form;

use App\Entity\Film;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Sodium\add;

class FilmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Nom',TextType::class,[
                "attr" =>[
                    "class" => "form-control"
                ]
            ])
            ->add('Description',TextType::class,[
                "attr" =>[
                    "class" => "form-control"
                ]
            ])
            ->add('DateSortie', DateType::class,[
                "attr" =>[
                    "class" => "form-control"
                ]
            ])
            ->add('image', FileType::class)
            ->add('Video')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Film::class,
        ]);
    }
}
