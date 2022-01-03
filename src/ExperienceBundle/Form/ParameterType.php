<?php

namespace ExperienceBundle\Form;

use ExperienceBundle\Entity\Experience;
use ExperienceBundle\Entity\Parameter;
use ExperienceBundle\Entity\ParameterValueBool;
use ExperienceBundle\Entity\ParameterValueFloat;
use ExperienceBundle\Entity\ParameterValueList;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParameterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder
        ->add('name', TextType::class, array(
          'label' => 'Nom du paramètre',
          'required' => true,
          'error_bubbling' => true
        ))
        ->add('parameterType', ChoiceType::class, array(
          'label' => 'Type de paramètre',
          'required' => true,
          'choices' => array_flip(Parameter::$parameterTypesAvailable),
          'attr' => array(
            'class' => 'parameterType'
          )
        ))
        ->add('weight', IntegerType::class, array(
          'label' => 'Poids',
          'required' => true
        ))
        ->add('unit', TextType::class, array(
          'label' => 'Unité',
          'required' => false
        ))
        ->add('parameterValueFloat', ParameterValueFloatType::class, array(
          'label' => false,
          'required' => false,
        ))
        ->add('parameterValueBool', ParameterValueBoolType::class, array(
          'label' => false,
          'required' => false,
        ))
        ->add('systemName', TextType::class, array(
              'label' => 'Nom système',
              'required' => true,
        ))
        ->add('parameterValueLists', CollectionType::class, array(
          'entry_type' => ParameterValueListType::class,
          'by_reference' => false,
          'allow_add' => true,
          'allow_delete'=> true,
          'delete_empty' => true,
          'label' => false,
          'prototype_name' => '__list__'
        ));

      if($options['children_accepted'] == true):
        $builder->add('children', CollectionType::class, array(
          'entry_type' => ParameterType::class,
          'by_reference' => false,
          'allow_add' => true,
          'allow_delete'=> true,
          'delete_empty' => true,
          'label' => false,
          'prototype_name' => '__children__',
          'entry_options'  => array(
            'children_accepted' => false,
          )
        ));
      else:
        $builder->add('parentValue', TextType::class, array(
          'label' => 'Activer cette dépendance lorsque la valeur du parent sera',
          'required' => false,
          'attr' => array(
            'class' => 'parent-value'
          )
        ));
      endif;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ExperienceBundle\Entity\Parameter',
            'children_accepted' => true
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'parameter';
    }


}
