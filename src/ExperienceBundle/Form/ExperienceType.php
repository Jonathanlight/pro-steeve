<?php

namespace ExperienceBundle\Form;

use Doctrine\ORM\EntityRepository;
use ExperienceBundle\Entity\ExperienceType as TypeExperience;//Due to conflict with Type of Experience Entity
use ExperienceBundle\Entity\Support;
use ExperienceBundle\Entity\Server;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExperienceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('name', TextType::class, array(
            'label' => 'Nom de l\'expérience',
            'required' => false,
            'error_bubbling' => true
          ))
          ->add('support', EntityType::class, array(
            'class' => Support::class,
            'label' => 'Support',
            'placeholder' => '----- Support -----',
            'choice_label' => 'name',
            'choice_value' => 'id',
            'query_builder' => function (EntityRepository $er) {
              return $er->createQueryBuilder('s')
                        ->orderBy('s.name', 'ASC');
            },
            'required' => true,
            'error_bubbling' => true,
          ))
          ->add('experienceType', EntityType::class, array(
            'class' => TypeExperience::class,//Check 'use' to understand this class name
            'label' => 'Type d\'expérience',
            'placeholder' => '----- Type d\'expérience -----',
            'choice_label' => 'name',
            'choice_value' => 'id',
            'query_builder' => function (EntityRepository $er) {
              return $er->createQueryBuilder('te')
                        ->orderBy('te.name', 'ASC');
            },
            'required' => true,
            'error_bubbling' => true,
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
          ->add('server', EntityType::class, array(
            'class' => Server::class,
            'label' => 'Serveur',
            'placeholder' => '----- Serveur -----',
            'choice_label' => 'name',
            'choice_value' => 'id',
            'query_builder' => function (EntityRepository $er) {
              return $er->createQueryBuilder('s')
                        ->orderBy('s.name', 'ASC');
            },
            'required' => true,
            'error_bubbling' => true,
          ))
          ->add('script', TextType::class, array(
            'label' => 'Nom du script',
            'required' => false,
            'error_bubbling' => true
          ))
          ->add('requiredMemory', UnitType::class, array(
            'label' => 'Mémoire requise',
            'required' => true,
            'error_bubbling' => true,
            'unit' => 'Mo'
          ))
          ->add('requiredTime', UnitType::class, array(
            'label' => 'Temps d\'execution',
            'required' => true,
            'error_bubbling' => true,
            'unit' => 'secondes'
          ))
          ->add('parameters', CollectionType::class, array(
            'entry_type' => ParameterType::class,
            'by_reference' => false,
            'allow_add' => true,
            'allow_delete'=> true,
            'delete_empty' => true,
            'label' => false,
            'prototype_name' => '__parameter__'
          ))
          ->add('published', ChoiceType::class, array(
            'label' => 'Publiée',
            'required' => true,
            'choices'  => array(
              'Oui' => true,
              'Non' => false,
            ),
          ))
          ->add('submit', SubmitType::class, array(
            'label' => 'Enregistrer l\'expérience',
            'attr' => array(
              'class' => 'btn-default pull-right'
            )
          ))
          ->add('submit_2', SubmitType::class, array(
            'label' => 'Enregistrer l\'expérience',
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
            'data_class' => 'ExperienceBundle\Entity\Experience'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'experience';
    }


}
