<?php

namespace App\Form;

use App\Entity\Reponses;
use App\Entity\Questions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ReponsesType2 extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder

      ->add('libelle', TextType::class, [
        'mapped' => false,
        'label' => 'Libellé',
        'attr' => [
          'maxLength' => 255
        ]
      ])
      ->add('success', CheckboxType::class, [
        'mapped' => false,
        'label' => 'Succès',
        'required' => false
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Reponses::class,
    ]);
  }
}
