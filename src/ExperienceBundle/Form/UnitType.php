<?php

namespace ExperienceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UnitType extends AbstractType
{
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'unit' => 'Unit',
    ));
  }

  /**
   * {@inheritdoc}
   */
  public function buildView(FormView $view, FormInterface $form, array $options)
  {
    $view->vars['unit'] = $options['unit'];
  }

  public function getParent()
  {
    return IntegerType::class;
  }
}