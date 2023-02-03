<?php

namespace App\Form;

use App\Entity\Eleves;
use App\Entity\Classes;
use App\Entity\Questionnaires;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ClassesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'maxLength' => 50
                ]
            ])
            // ->add('eleves', EntityType::class, [
            //     'class' => Eleves::class,
            //     'choice_label' => 'fullname',
            //     'multiple' => true
            // ])
            ->add('active', CheckboxType::class, [
                'label' => 'Active',
                'required' => false
            ])
            // ->add('questionnaires', EntityType::class, [
            //     'class' => Questionnaires::class,
            //     'choice_label' => 'id'
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Classes::class,
        ]);
    }
}
