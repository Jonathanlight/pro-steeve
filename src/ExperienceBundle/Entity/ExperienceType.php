<?php

namespace ExperienceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\Exclude;

/**
 * ExperienceType
 *
 * @ORM\Table(name="experience_type")
 * @ORM\Entity(repositoryClass="ExperienceBundle\Repository\ExperienceTypeRepository")
 * @see ORM\
 * @see Exclude
 */
class ExperienceType
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
     * @var ArrayCollection|Collection
     *
     * @ORM\OneToMany(targetEntity="Experience", mappedBy="experienceType")
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
     * @return ExperienceType
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
     * Add experience
     *
     * @param Experience $experience
     *
     * @return ExperienceType
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

