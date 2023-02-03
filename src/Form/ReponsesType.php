<?php

namespace App\Form;

use App\Entity\Reponses;
use App\Entity\Questions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ReponsesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle', TextType::class, [
                'label' => 'Libellé',
                'attr' => [
                    'maxLength' => 255
                ]
            ])
            ->add('success', CheckboxType::class, [
                'label' => 'Succès'
            ]);
        // ->add('questions', EntityType::class, [
        //     'class' => Questions::class,
        //     'choice_label' => 'id',
        //     // On utilise pas multiple parce qu'on a une relation ManyToOne
        //     // On doit donc fournir un seul choix possible pour une réponse et non plusieurs.
        //     // 'multiple' => false (true par défaut)
        // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reponses::class,
        ]);
    }
}
