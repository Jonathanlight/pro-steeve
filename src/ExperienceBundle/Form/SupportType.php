<?php

namespace ExperienceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SupportType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder
        ->add('name', TextType::class, array(
          'label' => 'Support',
          'required' => false,
          'error_bubbling' => true
        ))
        ->add('description', TextareaType::class, array(
          'label' => 'Description',
          'required' => false,
          'attr' => array(
            'class' => 'wysiwyg'
          )
        ))
        ->add('images', CollectionType::class, array(
          'entry_type' => ImageType::class,
          'by_reference' => false,
          'allow_add' => true,
          'allow_delete'=> true,
          'delete_empty' => true,
          'label' => false
        ))
        ->add('submit', SubmitType::class, array(
          'label' => 'Enregistrer le support',
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
            'data_class' => 'ExperienceBundle\Entity\Support'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'support';
    }
}