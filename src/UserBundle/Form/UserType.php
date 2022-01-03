<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UserBundle\Entity\User;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder
        ->add('firstName', TextType::class, array(
          'label' => 'Prénom',
          'required' => false,
          'error_bubbling' => true
        ))
        ->add('lastName', TextType::class, array(
          'label' => 'Nom',
          'required' => false,
          'error_bubbling' => true
        ))
        ->add('username', TextType::class, array(
          'label' => 'Nom d\'utilisateur',
          'required' => true,
          'error_bubbling' => true
        ))
        ->add('email', EmailType::class, array(
          'label' => 'Adresse e-mail',
          'required' => true, // true: nécessaire pour FOS (éviter erreur username cannot be null)
          'error_bubbling' => true
        ))
        ->add('password', RepeatedType::class, array(
          'first_options'  => array('label' => 'Mot de passe'),
          'second_options' => array('label' => 'Confirmation du mot de passe'),
          'type' => PasswordType::class,
          'required' => $options['passwordRequired'],
          'error_bubbling' => true
        ))
        ->add('role',ChoiceType::class, array(
          'label'   => 'Rôle',
          'choices' => User::$allRoles,
          'data' => $options['data']?$options['data']->getHighestRole():'',
          'mapped' => false
        ))
        ->add('submit', SubmitType::class, array(
          'label' => 'Enregistrer l\'utilisateur',
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
            'data_class' => 'UserBundle\Entity\User',
            'passwordRequired' => true
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'user';
    }


}
