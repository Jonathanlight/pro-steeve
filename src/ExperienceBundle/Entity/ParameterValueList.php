<?php

namespace ExperienceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ParameterValueList
 *
 * @ORM\Table(name="parameter_value_list")
 * @ORM\Entity(repositoryClass="ExperienceBundle\Repository\ParameterValueListRepository")
 * @see ORM\
 */
class ParameterValueList
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
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;

    /**
     * @var int
     *
     * @ORM\Column(name="weight", type="integer")
     */
    private $weight;

    /**
     * @var bool
     *
     * @ORM\Column(name="defaultValue", type="boolean")
     */
    private $defaultValue;

    /**
     * @var Parameter
     *
     * @ORM\ManyToOne(targetEntity="Parameter", inversedBy="parameterValueLists", cascade={"persist"})
     */
    private $parameter;

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
     * Set value
     *
     * @param string $value
     *
     * @return ParameterValueList
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     *
     * @return ParameterValueList
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
     * Set defaultValue
     *
     * @param boolean $defaultValue
     *
     * @return ParameterValueList
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }

    /**
     * Get defaultValue
     *
     * @return bool
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * Set parameter
     *
     * @param Parameter $parameter
     *
     * @return ParameterValueList
     */
    public function setParameter(Parameter $parameter = null)
    {
      $this->parameter = $parameter;

      return $this;
    }

    /**
     * Get parameter
     *
     * @return Parameter
     */
    public function getParameter()
    {
      return $this->parameter;
    }
}

