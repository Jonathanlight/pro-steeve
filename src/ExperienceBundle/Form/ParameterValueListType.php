<?php

namespace ExperienceBundle\Form;

use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParameterValueListType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder
        ->add('value', TextType::class, array(
          'label' => 'Valeur',
          'required' => true,
          'error_bubbling' => true,
          'attr' => array(
            'class' => 'value'
          )
        ))
        ->add('weight', IntegerType::class, array(
          'label' => 'Poids',
          'required' => true
        ))
        ->add('defaultValue', ChoiceType::class, array(
          'label' => 'Valeur par défaut',
          'required' => true,
          'choices'  => array(
              'False' => false,
              'True' => true
          ),
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
            'data_class' => 'ExperienceBundle\Entity\ParameterValueList'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'parametervaluelist';
    }


}
