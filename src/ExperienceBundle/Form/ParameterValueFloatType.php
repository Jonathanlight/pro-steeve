<?php

namespace ExperienceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParameterValueFloatType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder
        ->add('min', NumberType::class, array(
          'label' => 'Valeur minimale',
          'required' => true,
          'attr' => array(
            'class' => 'min'
          )
        ))
        ->add('max', NumberType::class, array(
          'label' => 'Valeur maximale',
          'required' => true,
          'attr' => array(
            'class' => 'max'
          )
        ))
        ->add('step', NumberType::class, array(
          'label' => 'Incrément',
          'required' => true,
          'attr' => array(
            'class' => 'step'
          )
        ))
        ->add('defaultValue', NumberType::class, array(
          'label' => 'Valeur par défaut',
          'required' => true,
          'attr' => array(
            'class' => 'defaultValue'
          )
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ExperienceBundle\Entity\ParameterValueFloat'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'parametervaluefloat';
    }


}
