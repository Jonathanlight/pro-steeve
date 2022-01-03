<?php

namespace ExperienceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder
        ->add('name', TextType::class, array(
          'label' => 'Serveur',
          'required' => true,
          'error_bubbling' => true
        ))
        ->add('memory', UnitType::class, array(
          'label' => 'Mémoire',
          'required' => true,
          'error_bubbling' => true,
          'unit' => 'Mo'
        ))
        ->add('address', TextType::class, array(
          'label' => 'Adresse',
          'required' => true,
          'error_bubbling' => true
        ))
        ->add('submit', SubmitType::class, array(
          'label' => 'Enregistrer le serveur',
          'attr' => array(
            'class' => 'btn-default pull-right'
          )
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ExperienceBundle\Entity\Server'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'server';
    }


}
