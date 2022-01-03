<?php
namespace ExperienceBundle\Service;

use ExperienceBundle\Entity\Experience;
use ExperienceBundle\Entity\Parameter;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ExperiencePreSave{
  public function __construct(TokenStorage $tokenStorage){
    $this->tokenStorage = $tokenStorage;
  }

  /**
   * Prepare related entities for Experience
   *
   * @param Experience $experience
   */
  public function prepareRelatedEntities(Experience &$experience){
    if($experience->getParameters() !== null):
      foreach ($experience->getParameters() as $parameter):
        /**
         * @var Parameter $parameter
         */
        switch ($parameter->getParameterType()):
          //Nombre -> Related to ParameterValueFloat
          case '0':
            //Remove List and Bool
            if($parameter->getParameterValueLists() !== null):
              $parameter->getParameterValueLists()->clear();
            endif;
            $parameter->setParameterValueBool();
            break;
          //Liste -> Related to ParameterValueList
          case '1':
            //Remove Float and Bool
            $parameter->setParameterValueFloat();
            $parameter->setParameterValueBool();
            break;
          //Booléen -> Related to ParameterValueBool
          case '2':
            //Remove Float and List
            $parameter->setParameterValueFloat();
            if($parameter->getParameterValueLists() !== null):
              $parameter->getParameterValueLists()->clear();
            endif;
            break;
        endswitch;
      endforeach;
    endif;
  }
}