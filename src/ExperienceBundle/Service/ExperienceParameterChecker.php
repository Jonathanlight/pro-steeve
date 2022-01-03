<?php
namespace ExperienceBundle\Service;

use Doctrine\ORM\EntityManager;
use ExperienceBundle\Entity\Parameter;

class ExperienceParameterChecker
{

  public function __construct()
  {
  }

  /**
   * Check if a parameter is allowed to be send
   * When a parameter child is not showing by its parent value, we must not submit him
   * @param Parameter $parameter
   * @param String $value
   * @param String $valueParent
   * @return boolean
   */
  public function isAuthorizeParameter(Parameter $parameter, $value, $valueParent = null){
    if($parameter->getParent() === null):
      return true;
    endif;

    //Rewrite parent value when it is a boolean parameter
    if($parameter->getParent()->getParameterType() === 2):
      $valueParent = $valueParent==="1"?"True":"False";
    endif;
    //Rewrite value when it is a boolean parameter
    if($parameter->getParameterType() === 2):
      $value = $value==="1"?"True":"False";
    endif;

    $authorizeValues = explode(',',$parameter->getParentValue());
    if($authorizeValues[0] == "")://When a child has not parentValue defined
      return true;//Always authorize
    endif;
    $return = in_array($valueParent,$authorizeValues);

    return $return;
  }

}
?>