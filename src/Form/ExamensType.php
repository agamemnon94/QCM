<?php

namespace App\Form;

use App\Entity\Eleves;
use App\Entity\Examens;
use App\Entity\Questionnaires;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExamensType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('eleve', EntityType::class, [
                'required' => false,
                'class' => Eleves::class,
                'choice_label' => 'fullName',
                'multiple' => true
            ])
            ->add('questionnaire', EntityType::class, [
                'required' => false,
                'class' => Questionnaires::class,
                'choice_label' => 'formCode',
                'multiple' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Examens::class,
        ]);
    }
}
