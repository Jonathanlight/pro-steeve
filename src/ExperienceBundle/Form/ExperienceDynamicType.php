<?php

namespace ExperienceBundle\Form;

use ExperienceBundle\Entity\Experience;
use ExperienceBundle\Entity\Parameter;
use ExperienceBundle\Entity\ParameterValueList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use ExperienceBundle\Entity\ParameterValueFloat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ExperienceDynamicType extends AbstractType
{
    private $builder;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->builder = $builder;
        $experience = $options['data'];

        foreach ($experience->getParameters() as $parameter){
          $this->buildField($parameter);
        }

        $builder->add('submit', SubmitType::class, array(
            'label' => 'Lancer cette expérience',
            'attr' => array(
                'class' => 'btn-primary pull-right'
            )
        ));

    }

    //Contruction des champs selon leur type
    public function buildField(Parameter $parameter){
      if($parameter->getParameterType() == 0){
        $this->genNumberField($parameter);
      }
      elseif($parameter->getParameterType() == 1){
        $this->genListField($parameter);
      }
      elseif($parameter->getParameterType() == 2){
        $this->genBoolField($parameter);
      }
    }

    //Contruction des dépendances s'il y en a
    public function buildChildren(Parameter $parameter){
      $children = $parameter->getChildren();
      if(count($children) > 0):
        foreach ($children as $child):
          $this->buildField($child);
        endforeach;
      endif;
    }

    //Gestion des attribus nécessaire à une dépendance
    public function getAttrChild(Parameter $parameter){
      if($parameter->getParent() !== null)://Uniquement si le paramètre est une dépendance
        $attrChild = array(
          'class' => 'child',
          'data-parent' => $parameter->getParent()->getId(),
          'data-parentvalue' => $parameter->getParentValue(),
          'class-container' => 'container_child'
        );
      else://Cas particulier, on donne la class au parent
        $attrChild = array(
          'class' => 'parent',
          'class-container' => 'container_parent'
        );
      endif;

      return $attrChild;
    }

    public function genNumberField(Parameter $parameter){
        $builder = $this->builder;
        /**
         * @var $builder FormBuilderInterface
         */

        $builder->add($parameter->getId(), TextType::class, array(
            'label' => $parameter->getName(),
            'mapped' => false,
            'attr' => $this->array_merge(array(
                'data-slider-id' => 'experience_floatParam_'.$parameter->getId().'Slider',
                'data-slider-min' => $parameter->getParameterValueFloat()->getMin(),
                'data-slider-max' => $parameter->getParameterValueFloat()->getMax(),
                'data-slider-step' => $parameter->getParameterValueFloat()->getStep(),
                'data-slider-value' => $parameter->getParameterValueFloat()->getDefaultValue(),
                'data-unit' => $parameter->getUnit(),
                'class' => 'slider-cursor'
            ), $this->getAttrChild($parameter))
        ));

        $this->buildChildren($parameter);
    }

    public function genListField(Parameter $parameter){
        $vals = array();
        $default = null;
        foreach($parameter->getParameterValueLists() as $value):
            /**
             * @var ParameterValueList $value
             */
            $vals[$value->getValue()] = $value->getValue();
            if($value->getDefaultValue() === true):
                $default = $value->getValue();
            endif;
        endforeach;

        $builder = $this->builder;
        /**
         * @var $builder FormBuilderInterface
         */

        $builder->add($parameter->getId(), ChoiceType::class, array(
            'choices' => $vals,
            'label' => $parameter->getName(),
            'mapped' => false,
            'data' => $default,
            'attr' => $this->array_merge(array(
              'data-unit' => $parameter->getUnit(),
              'class' => 'form-control'
            ), $this->getAttrChild($parameter))
        ));

        $this->buildChildren($parameter);
    }

    public function genBoolField(Parameter $parameter){

        $builder = $this->builder;
        /**
         * @var $builder FormBuilderInterface
         */

        //Valeur par défault
        $attr = array(
          'data-toggle' => 'toggle',
          'data-unit' => $parameter->getUnit()
        );
        if($parameter->getParameterValueBool()->getDefaultValue()):
          $attr = $this->array_merge($attr, array(
            'data-color' => 'primary',
            'checked' => 'checked',
          ));
        endif;

        $builder->add($parameter->getId(), CheckboxType::class, array(
            'label' => $parameter->getName(),
            'mapped' => false,
            'required' => false,
            'attr' =>  $this->array_merge($attr, $this->getAttrChild($parameter))
        ));

        $this->buildChildren($parameter);
    }

  /**
   * Fonction de merge récursif qui concatène les clés identiques contrairement à la fonction native de php.
   *
   * @param $a
   * @param $b
   *
   * @return array
   */
    public function array_merge($a, $b){
      $array = array();
      foreach(array_merge_recursive($a, $b) as $key => $value):
        if(is_array($value)):
          foreach ($value as $key2 => $value2):
            if(isset($array[$key])):
              $array[$key] .= $value2.' ';
            else:
              $array[$key] = $value2.' ';
            endif;
          endforeach;
          $array[$key] = substr($array[$key], 0, -1);
        else:
          $array[$key] = $value;
        endif;
      endforeach;
      return $array;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ExperienceBundle\Entity\Experience',
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
