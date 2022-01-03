<?php

namespace ExperienceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ParameterValueFloat
 *
 * @ORM\Table(name="parameter_value_float")
 * @ORM\Entity(repositoryClass="ExperienceBundle\Repository\ParameterValueFloatRepository")
 * @see ORM\
 */
class ParameterValueFloat
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
     * @var float
     *
     * @ORM\Column(name="min", type="float")
     */
    private $min;

    /**
     * @var float
     *
     * @ORM\Column(name="max", type="float")
     */
    private $max;

    /**
     * @var float
     *
     * @ORM\Column(name="step", type="float")
     */
    private $step;

    /**
     * @var float
     *
     * @ORM\Column(name="defaultValue", type="float")
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
     * Set min
     *
     * @param float $min
     *
     * @return ParameterValueFloat
     */
    public function setMin($min)
    {
        $this->min = $min;

        return $this;
    }

    /**
     * Get min
     *
     * @return float
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * Set max
     *
     * @param float $max
     *
     * @return ParameterValueFloat
     */
    public function setMax($max)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * Get max
     *
     * @return float
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * Set step
     *
     * @param float $step
     *
     * @return ParameterValueFloat
     */
    public function setStep($step)
    {
        $this->step = $step;

        return $this;
    }

    /**
     * Get step
     *
     * @return float
     */
    public function getStep()
    {
        return $this->step;
    }

    /**
     * Set defaultValue
     *
     * @param float $defaultValue
     *
     * @return ParameterValueFloat
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }

    /**
     * Get defaultValue
     *
     * @return float
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }
}

