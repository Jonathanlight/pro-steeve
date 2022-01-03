<?php

namespace ExperienceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;

/**
 * Server
 *
 * @ORM\Table(name="server")
 * @ORM\Entity(repositoryClass="ExperienceBundle\Repository\ServerRepository")
 * @see ORM\
 * @see Exclude
 */
class Server
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
     * @ORM\Column(name="Name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="Memory", type="integer")
     */
    private $memory;

    /**
     * @var int
     *
     * @ORM\Column(name="Address", type="string", length=255)
     */
    private $address;

    /**
     * @var ArrayCollection|Collection
     *
     * @ORM\OneToMany(targetEntity="Experience", mappedBy="server")
     * @Exclude()
     */
    private $experiences;

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
     * @return Server
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
     * Set memory
     *
     * @param integer $memory
     *
     * @return Server
     */
    public function setMemory($memory)
    {
        $this->memory = $memory;

        return $this;
    }

    /**
     * Get memory
     *
     * @return int
     */
    public function getMemory()
    {
        return $this->memory;
    }

    /**
     * Set address
     *
     * @param integer $address
     *
     * @return Server
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return int
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Add experience
     *
     * @param Experience $experience
     *
     * @return Server
     */
    public function addExperience(Experience $experience)
    {
      $this->experiences[] = $experience;

      return $this;
    }

    /**
     * Remove experience
     *
     * @param Experience $experience
     */
    public function removeExperience(Experience $experience)
    {
      $this->experiences->removeElement($experience);
    }

    /**
     * Get experiences
     *
     * @return Collection
     */
    public function getExperiences()
    {
      return $this->experiences;
    }
}

