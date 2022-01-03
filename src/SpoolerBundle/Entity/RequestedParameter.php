<?php

namespace SpoolerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ExperienceBundle\Entity\Parameter;

/**
 * RequestedParameter
 *
 * @ORM\Table(name="requested_parameter")
 * @ORM\Entity(repositoryClass="SpoolerBundle\Repository\RequestedParameterRepository")
 * @see ORM\
 */
class RequestedParameter
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
     * @var SpoolerItem
     *
     * @ORM\ManyToOne(targetEntity="SpoolerItem", inversedBy="requestedParameter", cascade={"persist"})
     */
    private $spoolerItem;

    /**
     * @var Parameter
     *
     * @ORM\ManyToOne(targetEntity="\ExperienceBundle\Entity\Parameter", inversedBy="requestedParameter", cascade={"persist"})
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
     * @return RequestedParameter
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
     * Set spoolerItem
     *
     * @param SpoolerItem $spoolerItem
     *
     * @return RequestedParameter
     */
    public function setSpooleritem(SpoolerItem $spoolerItem = null)
    {
        $this->spoolerItem = $spoolerItem;
        if($spoolerItem){
            $spoolerItem->addRequestedParameter($this);
        }

        return $this;
    }

    /**
     * Get spoolerItem
     *
     * @return SpoolerItem
     */
    public function getSpoolerItem()
    {
        return $this->spoolerItem;
    }

    /**
     * Set parameter
     *
     * @param Parameter $parameter
     *
     * @return RequestedParameter
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
