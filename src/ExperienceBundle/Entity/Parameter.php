<?php

namespace ExperienceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use SpoolerBundle\Entity\RequestedParameter;

/**
 * Parameter
 *
 * @ORM\Table(name="parameter")
 * @ORM\Entity(repositoryClass="ExperienceBundle\Repository\ParameterRepository")
 * @see ORM\
 */
class Parameter
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var array
     */
    static $parameterTypesAvailable = array(
      0 => 'Nombre',
      1 => 'Liste',
      2 => 'Booléen'
    );

    /**
     * @var int
     *
     * @ORM\Column(name="parameterType", type="integer")
     */
    private $parameterType;

    /**
     * @var string
     *
     * @ORM\Column(name="unit", type="string", length=255, nullable=true)
     */
    private $unit;

    /**
     * @var int
     *
     * @ORM\Column(name="weight", type="integer")
     */
    private $weight;

    /**
     * @var Experience
     *
     * @ORM\ManyToOne(targetEntity="Experience", inversedBy="parameters", cascade={"persist"})
     */
    private $experience;

    /**
     * @var ArrayCollection|Collection
     *
     * @ORM\OneToMany(targetEntity="\SpoolerBundle\Entity\RequestedParameter", mappedBy="parameter", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $requestedParameters;

    /**
     * @var ParameterValueFloat
     *
     * @ORM\OneToOne(targetEntity="ParameterValueFloat", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $parameterValueFloat;

    /**
     * @var ArrayCollection|Collection
     *
     * @ORM\OneToMany(targetEntity="ParameterValueList", mappedBy="parameter", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"weight" = "ASC"})
     */
    private $parameterValueLists;

    /**
     * @var ParameterValueBool
     *
     * @ORM\OneToOne(targetEntity="ParameterValueBool", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $parameterValueBool;

    /**
     * @var string
     *
     * @ORM\Column(name="systemName", type="string", length=255, nullable=true)
     */
    private $systemName;

    /**
     * @var ArrayCollection|Collection
     * One Parameter has Many Parameters.
     * @ORM\OneToMany(targetEntity="Parameter", mappedBy="parent", cascade={"persist", "remove"})
     */
    private $children;

    /**
     * @var Parameter
     * Many Parameters have One Parameter.
     * @ORM\ManyToOne(targetEntity="Parameter", inversedBy="children")
     */
    private $parent;

    /**
     * @var string
     *
     * @ORM\Column(name="parentValue", type="string", length=255, nullable=true)
     */
    private $parentValue;

    /**
     * Constructor
     */
    public function __construct()
    {
      $this->requestedParameters = new ArrayCollection();
      $this->parameterValueLists = new ArrayCollection();
      $this->children = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Parameter
     */
    public function setName($name)
    {
      $this->name = $name;

      return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
      return $this->name;
    }

    /**
     * Set parameterType
     *
     * @param integer $parameterType
     *
     * @return Parameter
     */
    public function setParameterType($parameterType)
    {
        $this->parameterType = $parameterType;

        return $this;
    }

    /**
     * Get parameterType
     *
     * @return int
     */
    public function getParameterType()
    {
        return $this->parameterType;
    }

    /**
     * Get readable parameterType
     *
     * @return integer
     */
    public function getParameterTypeReadable()
    {
      return self::$parameterTypesAvailable[$this->parameterType];
    }

    /**
     * Set unit
     *
     * @param string $unit
     *
     * @return Parameter
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     *
     * @return Parameter
     */
    public function setWeight($weight)
    {
      $this->weight = $weight;

      return $this;
    }

    /**
     * Get weight
     *
     * @return int
     */
    public function getWeight()
    {
      return $this->weight;
    }

    /**
     * Set experience
     *
     * @param Experience $experience
     *
     * @return Parameter
     */
    public function setExperience(Experience $experience = null)
    {
      $this->experience = $experience;

      return $this;
    }

    /**
     * Get experience
     *
     * @return Experience
     */
    public function getExperience()
    {
      return $this->experience;
    }

    /**
     * Set parameterValueFloat
     *
     * @param ParameterValueFloat|null $parameterValueFloat
     *
     * @return Parameter
     */
    public function setParameterValueFloat(ParameterValueFloat $parameterValueFloat = null)
    {
      $this->parameterValueFloat = $parameterValueFloat;

      return $this;
    }

    /**
     * Get parameterValueFloat
     *
     * @return ParameterValueFloat
     */
    public function getParameterValueFloat()
    {
      return $this->parameterValueFloat;
    }

    /**
     * Add parameterValueLists
     *
     * @param ParameterValueList $parameterValueList
     *
     * @return Parameter
     */
    public function addParameterValueList(ParameterValueList $parameterValueList)
    {
      $this->parameterValueLists[] = $parameterValueList;

      $parameterValueList->setParameter($this);

      return $this;
    }

    /**
     * Remove parameterValueLists
     *
     * @param ParameterValueList $parameterValueList
     */
    public function removeParameterValueList(ParameterValueList $parameterValueList)
    {
      $this->parameterValueLists->removeElement($parameterValueList);
    }

    /**
     * Get parameterValueLists
     *
     * @return Collection
     */
    public function getParameterValueLists()
    {
      return $this->parameterValueLists;
    }

    /**
     * Set parameterValueBool
     *
     * @param ParameterValueBool|null $parameterValueBool
     *
     * @return Parameter
     */
    public function setParameterValueBool(ParameterValueBool $parameterValueBool = null)
    {
      $this->parameterValueBool = $parameterValueBool;

      return $this;
    }

    /**
     * Get parameterValueBool
     *
     * @return ParameterValueBool
     */
    public function getParameterValueBool()
    {
      return $this->parameterValueBool;
    }

    /**
     * @return string
     */
    public function getSystemName()
    {
        return $this->systemName;
    }

    /**
     * @param string $systemName
     *
     * @return Parameter
     */
    public function setSystemName($systemName)
    {
        $this->systemName = $systemName;
        return $this;
    }

    /**
     * Add requestedParameter
     *
     * @param RequestedParameter $requestedParameter
     *
     * @return Parameter
     */
    public function addRequestedParameter(RequestedParameter $requestedParameter)
    {
        $this->requestedParameters[] = $requestedParameter;

        return $this;
    }

    /**
     * Remove requestedParameter
     *
     * @param RequestedParameter $requestedParameter
     */
    public function removeRequestedParameter(RequestedParameter $requestedParameter)
    {
        $this->requestedParameters->removeElement($requestedParameter);
    }

    /**
     * Get requestedParameters
     *
     * @return Collection
     */
    public function getRequestedParameters()
    {
        return $this->requestedParameters;
    }

    /**
     * Add child
     *
     * @param Parameter $parameter
     *
     * @return Parameter
     */
    public function addChild(Parameter $parameter)
    {
      $this->children[] = $parameter;

      $parameter->setParent($this);

      return $this;
    }

    /**
     * Remove child
     *
     * @param Parameter $parameter
     */
    public function removeChild(Parameter $parameter)
    {
      $this->children->removeElement($parameter);
    }

    /**
     * Get children
     *
     * @return Collection
     */
    public function getChildren()
    {
      return $this->children;
    }

    /**
     * Set parent
     *
     * @param Parameter $parent
     *
     * @return Parameter
     */
    public function setParent(Parameter $parent = null)
    {
      $this->parent = $parent;

      return $this;
    }

    /**
     * Get parent
     *
     * @return Parameter
     */
    public function getParent()
    {
      return $this->parent;
    }

    /**
     * Set parentValue
     *
     * @param string $parentValue
     *
     * @return Parameter
     */
    public function setParentValue($parentValue)
    {
      $this->parentValue = $parentValue;
      return $this;
    }

    /**
     * Get parentValue
     *
     * @return string
     */
    public function getParentValue()
    {
      return $this->parentValue;
    }
}
