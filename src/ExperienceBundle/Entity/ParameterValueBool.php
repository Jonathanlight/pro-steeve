<?php

namespace ExperienceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ParameterValueBool
 *
 * @ORM\Table(name="parameter_value_bool")
 * @ORM\Entity(repositoryClass="ExperienceBundle\Repository\ParameterValueBoolRepository")
 * @see ORM\
 */
class ParameterValueBool
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
     * @var bool
     *
     * @ORM\Column(name="defaultValue", type="boolean")
     */
    private $defaultValue;

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
     * Set defaultValue
     *
     * @param boolean $defaultValue
     *
     * @return ParameterValueBool
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
}

