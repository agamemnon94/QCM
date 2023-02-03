<?php

namespace App\Form;

use App\Entity\Eleves;
use App\Entity\Classes;
use App\Entity\Examens;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ElevesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'attr' => [
                    'maxLenght' => 50
                ]
            ])
            ->add('lastname', TextType::class, [
                'attr' => [
                    'maxLenght' => 50
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => false,
                'attr' => [
                    'maxLength' => 50
                ]
            ])
            // ->add('examen', EntityType::class, [
            //     'class' => Examens::class,
            //     'choice_label' => 'id'
            // ])
            // ->add('eleve_classe', EntityType::class, [
            //     'class' => Classes::class,
            //     'choice_label' => 'name',
            //     'multiple' => true
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Eleves::class,
        ]);
    }
}
