<?php

namespace App\Form;

use App\Entity\Eleves;
use App\Entity\Classes;
use App\Entity\Questions;
use App\Entity\Questionnaires;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class QuestionnairesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('form_code', TextType::class, [
                'label' => 'Code',
                'attr' => [
                    'maxlength' => 15
                ]

            ])
            ->add('consigne', TextareaType::class, [
                'label' => 'Consignes',
                'attr' => [
                    'maxLength' => 2000
                ],
                'help' => '3000 caractères maximum'
            ])
            // ->add('eleves', EntityType::class, [
            //     'required' => false,
            //     'class' => Eleves::class,
            //     'choice_label' => 'fullName',
            //     'multiple' => true


            // ])
            // ->add('questionnaire_classe', EntityType::class, [
            //     'required' => false,
            //     'class' => Classes::class,
            //     'choice_label' => 'name',
            //     'multiple' => true,
            //     'label' => 'Classe'
            // ])
            // ->add('questionnaire_question', EntityType::class, [
            //     'class' => Questions::class,
            //     'choice_label' => 'id',
            //     'multiple' => true
            // ])
        ;
    }

    public function fuildFormTwo(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('questions', CollectionType::class, [

            'entry_type' => Questions::class,
            'entry_options' => ['label' => false],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Questionnaires::class,
        ]);
    }
}
